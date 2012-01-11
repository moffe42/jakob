<?php
namespace WAYF;

class SAMLException extends \Exception {}

class SAML {
    private $_xp;
    private $_message;

    public function __sleep() {
        return array('message');
    }

    public function __wakeup() {
        $this->_receiveMessage();
    }

    private function _receiveMessage($message = null) {
        // WHY?? It should always be parsed
        if (!$this->_message) {
            $this->_message = $message;
        }
        $document = new \DOMDocument();
        $document->loadXML($this->_message);
        $this->_xp = new \DomXPath($document);
        $this->_xp->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $this->_xp->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');
        $this->_xp->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');	
    }

    public function receiveRequest($request, $md) {
        $this->_receiveMessage(gzinflate(base64_decode($request)));
        $this->_verifySignatureAndIssuerEtc($this->_xp, $md);
    }

    public function receiveResponse($response, $md) {
        $this->_receiveMessage(base64_decode($response));
        $this->_verifySignature($md['idp_certificate'], $this->_xp, true);
        $this->_verifyTimingEtc($this->_xp, $md);
    }

    public function getAttributes()
    {
        $res = array();
        $attributes  = $this->_xp->query("/samlp:Response/saml:Assertion/saml:AttributeStatement/saml:Attribute");
        foreach($attributes as $attribute) {
            $valuearray = array();
            $values = $this->_xp->query('./saml:AttributeValue', $attribute);
            foreach($values as $value) {
                $valuearray[] = $value->textContent;
            }
            $res[$attribute->getAttribute('Name')] = $valuearray;
        }
        return $res;
    }

    private function _verifySignatureAndIssuerEtc($_xp, $md)
    {
        // we need the raw urlencoded string - re-urlencode might not work depending on the local urlencode implementation
        $raw = array();
        foreach (e_xplode("&", $_SERVER['QUERY_STRING']) as $parameter) {
            if (preg_match("/^(SAMLRequest|RelayState|SigAlg)=(.*)$/", $parameter, $keyAndValue)) {
                $raw[$keyAndValue[1]] = $keyAndValue[2];
            }
        }

        $queryString = 'SAMLRequest=' . $raw['SAMLRequest'];
        if (isset($raw['RelayState'])) {
            $queryString .= '&RelayState=' . $raw['RelayState'];
        }
        $queryString .= '&SigAlg=' . $raw['SigAlg'];

        $publicKey = openssl_get_publickey("-----BEGIN CERTIFICATE-----\n" . chunk_split($md['sp_certificate'], 64) . "-----END CERTIFICATE-----");

        $issues = array();
        if (!openssl_verify($queryString, base64_decode($_GET['Signature']), $publicKey)) $issues[] = 'Error verifying incoming Request';

        if ($md['sp'] != $_xp->query('/samlp:AuthnRequest/saml:Issuer')->item(0)->textContent) $issues[] = 'Request from unknown serviceprovider';

        $skew = 1000;
        $aShortWhileAgo = gmdate('Y-m-d\TH:i:s\Z', time() - $skew);
        $issueInstant = $_xp->query('/samlp:AuthnRequest/@IssueInstant')->item(0)->value;
        if ($aShortWhileAgo > $issueInstant) $issues[] = 'Request too old';

        $destination = $_xp->query('/samlp:AuthnRequest/@Destination')->item(0)->value;
        if ($destination != $md['idp']) $issues[] = 'Request not for us';

        $assertionConsumerServiceURL = $_xp->query('/samlp:AuthnRequest/@AssertionConsumerServiceURL')->item(0)->value;
        if ($assertionConsumerServiceURL != $md['acs']) $issues[] = 'Unknown AssertionConsumerServiceUrl';

        if (!empty($issues)) {
            throw new \WAYF\SAMLException('Problems detected with Request. ' . PHP_EOL. 'Issues: ' . PHP_EOL . implode(PHP_EOL, $issues));
        }
    }

    public function sendResponse($requestID, $md, $attributes = array(), $errormsg = '') {
        $as = "";
        foreach($attributes as $key => $values) {
            $as .= '<saml:Attribute Name="' . $key . '" NameFormat="urn:oasis:names:tc:SAML:2.0:attrname-format:basic">';
            foreach((array)$values as $value) {
                $as .= '<saml:AttributeValue xsi:type="xs:string">' . $value . '</saml:AttributeValue>';
            }
            $as .= '</saml:Attribute>';
        }

        $issuer = $md['idp'];
        $issueInstant = gmdate('Y-m-d\TH:i:s\Z', time());
        $notOnOrAfter = gmdate('Y-m-d\TH:i:s\Z', time() + 300);
        $responseID = '_' . sha1(uniqid(mt_rand(), true));
        $assertionID = '_' . sha1(uniqid(mt_rand(), true));
        $nameID = sha1(uniqid(mt_rand(), true));
        $sessionIndex = sha1(uniqid(mt_rand(), true));
        $destination = $md['acs'];

        $response = <<<eof
<samlp:Response xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" 
    ID="$responseID" Version="2.0" 
    IssueInstant="$issueInstant"
    Destination="$destination"
    InResponseTo="$requestID">
    <saml:Issuer>$issuer</saml:Issuer>
    <samlp:Status><samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Success" /></samlp:Status>
    <saml:Assertion xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema"
            ID="$assertionID" Version="2.0" 
            IssueInstant="$issueInstant">
        <saml:Issuer>$issuer</saml:Issuer>
        <saml:Subject>
          <saml:NameID Format="urn:oasis:names:tc:SAML:1.1:nameid-format:transient">$nameID</saml:NameID>
          <saml:SubjectConfirmation Method="urn:oasis:names:tc:SAML:2.0:cm:bearer">
            <saml:SubjectConfirmationData NotOnOrAfter="$notOnOrAfter"/>
         </saml:SubjectConfirmation>
       </saml:Subject>
        <saml:AuthnStatement AuthnInstant="$issueInstant" SessionIndex="$sessionIndex">
            <saml:AuthnContext>
                <saml:AuthnContextClassRef>
                    urn:oasis:names:tc:SAML:2.0:ac:classes:Password
                </saml:AuthnContextClassRef>
            </saml:AuthnContext>
        </saml:AuthnStatement>
       <saml:AttributeStatement>
            $as
        </saml:AttributeStatement>
    </saml:Assertion>
</samlp:Response>
eof;

        if ($errormsg) {
            $response = <<<eof
<samlp:Response xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" 
    ID="$responseID" Version="2.0" 
    IssueInstant="$issueInstant"
    Destination="$destination"
    InResponseTo="$requestID">
    <saml:Issuer>$issuer</saml:Issuer>
    <samlp:Status>
        <samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Responder">	
            <samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:AuthnFailed" />
            <samlp:StatusMessage>$errormsg</samlp:StatusMessage>
        </samlp:StatusCode>
    </samlp:Status>
</samlp:Response>
eof;
        }

        $this->_signResponse($response, $md, false);

        $rs = '';
        if (isset($_GET['RelayState'])) {
            $rs = '<input type="hidden" name="RelayState" value="' . htmlspecialchars($_GET['RelayState']) . '">';
        }
        $response = htmlspecialchars(base64_encode($response));

        $action = $md['acs']; 
        print <<<eof
<hml><body onload="document.forms[0].submit()"><form method=POST action="$action">
$rs
<input type=hidden name=SAMLResponse value="$response">
</form></body></html>
eof;
        exit;
    }

    private function _signResponse(&$response, $md, $assertion = true)
    {
        $document = new DOMDocument();
        $document->loadXML($response);
        $_xp = new DomXPath($document);
        $_xp->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
        $_xp->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $_xp->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');
        if ($assertion) {
            $xml = $_xp->query('/samlp:Response/saml:Assertion')->item(0);
            $id = $_xp->query('/samlp:Response/saml:Assertion/@ID')->item(0)->value;	
        } else {
            $xml = $document->documentElement;
            $id = $_xp->query('/samlp:Response/@ID')->item(0)->value;	
        }

        $canonicalXml = $xml->C14N(true, false);

        $digestValue = base64_encode(sha1($canonicalXml, TRUE));
        $uri = "#" . $id;
        $signedInfo = <<<eof
<ds:SignedInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
    <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#" />
    <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /> 
    <ds:Reference URI="$uri">
        <ds:Transforms>
            <ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />
            <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#" />
        </ds:Transforms>
        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" />
        <ds:DigestValue>$digestValue</ds:DigestValue>
    </ds:Reference>
</ds:SignedInfo>
eof;

        $document2 = new DOMDocument();
        $document2->loadXML($signedInfo);
        $canonicalXml2 = $document2->firstChild->C14N(true, false);

        $key = openssl_pkey_get_private("-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($md['idp_key'], 64) ."-----END RSA PRIVATE KEY-----");

        openssl_sign($canonicalXml2, $signatureValue, $key);

        openssl_free_key($key);

        $signatureValue = base64_encode($signatureValue);
        $certificate = $md['idp_certificate'];

        $signature = <<<eof
<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
    $signedInfo
    <ds:SignatureValue>
        $signatureValue
    </ds:SignatureValue>
    <ds:KeyInfo>
        <ds:X509Data>
            <ds:X509Certificate>
                $certificate
            </ds:X509Certificate>
        </ds:X509Data>
    </ds:KeyInfo>
</ds:Signature>	
eof;

        $document3 = new DOMDocument();
        $document3->loadXML($signature);

        if ($assertion) $refencenode = $_xp->query('/samlp:Response/saml:Assertion/saml:AttributeStatement')->item(0);
        else $refencenode = $_xp->query('/samlp:Response/samlp:Status')->item(0);

        $document3 = $document->importNode($document3->documentElement , true);

        $refencenode->parentNode->insertbefore($document3, $refencenode);
        $response = $document->saveXML();
    }

    private function _verifySignature($publicKey, $_xp, $assertion = true)
    {
        if ($assertion) $context = $_xp->query('/samlp:Response/saml:Assertion')->item(0);
        else $context = $_xp->query('/samlp:Response')->item(0);

        $signatureValue = base64_decode($_xp->query('ds:Signature/ds:SignatureValue', $context)->item(0)->textContent);
        $digestValue    = base64_decode($_xp->query('ds:Signature/ds:SignedInfo/ds:Reference/ds:DigestValue', $context)->item(0)->textContent);
        $id = $_xp->query('@ID', $context)->item(0)->value;

        $signedElement  = $context;
        $signature      = $_xp->query("ds:Signature", $signedElement)->item(0);    
        $signedInfo     = $_xp->query("ds:SignedInfo", $signature)->item(0)->C14N(true, false);
        $signature->parentNode->removeChild($signature);
        $canonicalXml = $signedElement->C14N(true, false);

        $publicKey = openssl_get_publickey("-----BEGIN CERTIFICATE-----\n" . chunk_split($publicKey, 64) . "-----END CERTIFICATE-----");

        if (!((sha1($canonicalXml, TRUE) == $digestValue) && openssl_verify($signedInfo, $signatureValue, $publicKey) == 1)) {
            throw new \WAYF\SAMLException('Error verifying incoming SAMLResponse' . PHP_EOL . openssl_error_string() . PHP_EOL . 'SAMLResponse: ' . print_r(htmlspecialchars($canonicalXml), 1));
        }
    }

    private function _verifyTimingEtc($_xp, $md)
    {
        $skew = 60;
        $aShortWhileAgo = gmdate('Y-m-d\TH:i:s\Z', time() - $skew);
        $inAShortWhile = gmdate('Y-m-d\TH:i:s\Z', time() + $skew);
        $issues = array();

        $destination = $_xp->query('/samlp:Response/@Destination')->item(0)->value;
        if ($destination != null && $destination != $md['asc']) { // Destination is optional
            $issues[] = "Destination: {$message['_Destination']} is not here; message not destined for us";
        }

        $assertion = $_xp->query('/samlp:Response/saml:Assertion')->item(0);

        $subjectConfirmationData_NotBefore = $_xp->query('./saml:Subject/saml:SubjectConfirmation/saml:SubjectConfirmationData/@NotBefore', $assertion);
        if ($subjectConfirmationData_NotBefore->length  && $aShortWhileAgo < $subjectConfirmationData_NotBefore->item(0)->value) {
            $issues[] = 'SubjectConfirmation not valid yet';
        }

        $subjectConfirmationData_NotOnOrAfter = $_xp->query('./saml:Subject/saml:SubjectConfirmation/saml:SubjectConfirmationData/@NotOnOrAfter', $assertion);
        if ($subjectConfirmationData_NotOnOrAfter->length && $inAShortWhile >= $subjectConfirmationData_NotOnOrAfter->item(0)->value) {
            $issues[] = 'SubjectConfirmation too old';
        }

        $conditions_NotBefore = $_xp->query('./saml:Conditions/@NotBefore', $assertion);
        if ($conditions_NotBefore->length && $aShortWhileAgo > $conditions_NotBefore->item(0)->value) {
            $issues[] = 'Assertion Conditions not yet valid';
        }

        $conditions_NotOnOrAfter = $_xp->query('./saml:Conditions/@NotOnOrAfter', $assertion);
        if ($conditions_NotOnOrAfter->length && $aShortWhileAgo >= $conditions_NotOnOrAfter->item(0)->value) {
            $issues[] = 'Assertions Condition too old';
        }

        $authStatement_SessionNotOnOrAfter = $_xp->query('./saml:AuthStatement/@SessionNotOnOrAfter', $assertion);
        if ($authStatement_SessionNotOnOrAfter->length && $aShortWhileAgo >= $authStatement_SessionNotOnOrAfter->item(0)->value) {
            $issues[] = 'AuthnStatement Session too old';
        }

        if (!empty($issues)) {
            throw new \WAYF\SAMLException('Problems detected with response. ' . PHP_EOL. 'Issues: ' . PHP_EOL . implode(PHP_EOL, $issues));
        }
    }

    public function sendRequest($providerids, $md) {
        $id =  '_' . sha1(uniqid(mt_rand(), true));
        $issueInstant = gmdate('Y-m-d\TH:i:s\Z', time());
        $sp = $md['sp'];
        $asc = $md['asc'];
        $sso = $md['sso'];
        $scoping = '';
        foreach((array)$providerids as $provider) {
            $scoping .= "<samlp:IDPEntry ProviderID=\"$provider\"/>";
        }
        if ($scoping) $scoping = '<samlp:Scoping><samlp:IDPList>'.$scoping . '</samlp:IDPList></samlp:Scoping>';

        $request = <<<eof
<?xml version="1.0"?>
<samlp:AuthnRequest
    ID="$id"
    Version="2.0"
    IssueInstant="$issueInstant"
    Destination="$sso"
    AssertionConsumerServiceURL="$asc" 
    ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" 
    xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol">
    <saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">$sp</saml:Issuer>
    $scoping
</samlp:AuthnRequest>
eof;

        $queryString = "SAMLRequest=" . urlencode(base64_encode(gzdeflate($request)));;
        $queryString .= '&SigAlg=' . urlencode('http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        $key = openssl_pkey_get_private("-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($md['sp_key'], 64) ."-----END RSA PRIVATE KEY-----");

        $signature = "";
        openssl_sign($queryString, $signature, $key, OPENSSL_ALGO_SHA1);
        openssl_free_key($key);

        header('Location: ' .  $md['sso'] . "?" . $queryString . '&Signature=' . urlencode(base64_encode($signature)));
        exit;
    }
}
