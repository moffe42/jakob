This page contains the original initial thoughts about JAKOB. These initial ideas have spanned several [use cases](UseCases.md), that form the basis of the design of JAKOB.

# JAKOB - An Attribute Collector #

Work only back channel.

Write connectors to the different systems (custom, SAML2 attribute query etc.)

SSP filter calls the extern JAKOB service.

Att-store (AS) delegates the LoA requirement etc. to JAKOB-config, so AS only receives requests that adhere to the LoA requirement (enforced by JAKOB), so all AS can reply to all requests.

WAYF prod setup: auth proc filter for specific SP's calls generic attritbute collector (JAKOB),  SP-tag is configured in JANUS.

Connectors: (Re)use what you already have, send specification to WAYF, who will write a connector. SAML2 or modern web + JSON/XML, SOAP or LDAP is recommended.

![http://jakob.googlecode.com/files/basic-design.png](http://jakob.googlecode.com/files/basic-design.png)

**Web-based admin interface**

Federated login, based on entitlement (for delegating administration to the different IdP´s)