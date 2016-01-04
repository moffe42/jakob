The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](http://www.ietf.org/rfc/rfc2119.txt).

# Introduction #
This documents describes the basic requirements for external data sources used in conjunction with JAKOB, together with some best practice advice.

# How to design API for JAKOB connectors #
When designing an API for a JAKOB connector to access, the main focus should be on speed. Your data source is only one in many that will be called together in order to collect all necessary attributes about a user. So the longer it takes to get a response from a data source, the longe a user have to wait before the user is redirected back to the requested service.

Two of the main issues to focus on is the interchange format and that the data source have enough ressources to complete the request in a reasonable time.

## Basic requirements ##
  * The API MUST be public accessible via one or more URL's (or IP address)
  * The request method SHOULD be GET or POST (GET is preferred)
  * The authentication against the API MUST not require human interaction
  * The number of requests for getting data SHOULD be keept to a bare minimum (one is preferred)
  * The interchange format SHOULD be as light weight as possible (JSON is preferred)

## Interchange format ##
The interchange format SHOULD as a bare minimun contain the following informations
  * The request status (A message together with a code is preferred)
  * The requested attributes about the user (if success)
  * The key used to identify the user in the request (Return the CPR of the user if the CPR was the key used to retrive attributes)

It is also a good idear to return the following informations. These requirements are OPTIONAL.
  * A unique id number for the request (This will make debugging easier in case of errors)
  * The API version (This will make it easier to upgrade the API's interchange format at a later time)

The following is an example of an interchange format that is light weight and easy to parse.

### Example ###
Below is a proposed example output of an external datasource, to be delivered back to a connector
```
{
    "id" : "v4j23h5vj5h3425v",
    "version" : "1.0",
    "userid" : "999999-8888",
    "status" : {
        "code" : 1,
        "msg" : "Success"
    },
    "attributes" : {
        "attribute1key" : "attribute1value",
        "attribute2key" : [
            "attribute2value1",
            "attribute2value2"
        ],
        "attribute3key" : "attribute3value"
    } 
}
```