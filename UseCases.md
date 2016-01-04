# JAKOB (Attribut Collector) Use Cases #

This page contains high level descriptions of use cases, that are the basis for the initial high level design and description of the JAKOB attribute collector.

The use cases are further refined to actual [user stories](UserStories.md), that form the basis for the actual design and implementation.




---


## Netlydbog.dk (Aarhus) ##
### Use Case 1 for netlydbog.dk ###
En bruger går ind på [netlydbog.dk] og logger ind med [NemID](https://www.nemid.nu/).
I forbindelse med indlogningen hentes informationer om hvilken kommune brugeren er tilknyttet.

  * Hun søger den ønskede titel frem og klikker herefter på ”Hent”
  * Filen downloades til hendes computer
  * Hun beslutter sig også at downloade bind 2 i serien, søger titlen frem og klikker på ”Hent”
  * Filen overføres til hendes computer

Det sker:
  * Ved indlogning sendes cpr-nr. fra NemID-login retur til attribut collectoren, som ud fra cpr-nr. har låneren registreret som bosiddende i x-købing kommune
  * Hun udstyres med rettigheder i forhold til X-købing kommune, hvorfor hun ikke selv skal angive hvilket bibliotek hun kommer fra
  * Biblioteket registreres for udlånet i administrationsystemet
  * Man skal være bosiddende i den kommune biblioteket ligger i for at kunne låne netlydbøger

### Use Case 2 for netlydbog.dk ###
En bruger browser rundt på [netlydbog.dk](https://netlydbog.dk/)
  * Klikker på ”Hent” for at downloade lydbogen til sin computer
  * Brugeren er ikke logget ind og redirectes derfor til login-siden for indlogning
  * Der vælges NemID login. I forbindelse med indlogning hentes information om brugeres bopælskommune
  * Kommunen har abonnement på netlydbog.dk så titlen må downloades

Det sker:
  * Cpr-nr. sendes fra NemID-login til Attribut Collector, som ud fra cpr-nr. tjekker låneren.
  * Cpr-nr. findes kun som biblioteksbruger på Statsbiblioteket
  * Statsbiblioteket har ikke abonnement på netlydbog.dk, så de lokale bibliotekssystemer i folkebiblioteker i Midtjylland forespørges om hvor brugeren hører til
  * Attribut Collector får svar fra Silkeborg Bibliotek og cpr-nr. knyttes til Silkeborg Bibliotek & Silkeborg Kommune
  * Der sendes besked til Silkeborg Bibliotek om at oprette låneren som bruger (digital bruger)
  * Hvordan Silkeborg Bibliotek håndtere dette er ikke en del af usecasen!

### Use Case 3 for netlydbog.dk ###
Et barn browser rundt på netlydbog.dk for at finde en lydbog han ønsker at høre på vej til skole. Da han finder den ønskede titlen klikker han på ”Lyt” for at streame lydbogen fra hans iPhone.

  * Da han ikke er logget ind, videresendes han til login siden
  * Da han ikke har et NemID, vælger han at logge ind med hans lånekort
  * Han skal selv vælge hvilket bibliotek han er tilknyttet

Det sker:
  * Ved indlogning tjekkes at brugeren er oprettet som låner på det valgte bibliotek


---


## E-ressource ezProxy (Statsbiblioteket, SB)) - USE CASE IMPLEMENTED ##
### Use Case 1 for E-ressource ezProxy ###
An end-user sits at home searching for a scientific article via the State Library (SB) search engine retrieval system or via an external search database.

The user wants to download a particular article and is therefore redirected to SB's link resolver, which returns a download link to the desired article.

There is only access to these scientific articles at SB, if the user has a specific user role (student or staff) at Aarhus University.

Since the end-user is sitting at home and not physically present at Aarhus University Campus while searching for the article, then the article is protected by a remote access proxy.

The remote access proxy has different login possibilities and among one of them the Danish NemID (Easylog-in) via WAYF.

If the end-user chooses NemID as login, then the attribute collector retrieves information about the end user's role (student or staff) AND affiliation (faculty at Aarhus University) from the SB user registry.If the user role and affiliation matches the remote access proxy's validation rules, the login is authorised and the requested download link for the article is provided.

  * SB must set up and configure a remote access proxy
  * SB must present SB's user directory as an attribute store, using the Danish CPR No. (social security number) to retrieve the user's role and affiliation
  * SB security module can be a normal secured SSL connection where data comes from a approved and fixed Ip address.
  * Used protocol can be JSON
  * WAYF sets up, the attribute collector to handle this use case
  * WAYF develops adapters for the data exchange between the attribute collector and the SB remote access proxy

#### API for SB's attrubute store ####

The remote access proxy's attribute store is called by:

`https://<domæne>/index.php?ukey=<ukey>&cpr=<cpr>`

where `<ukey>` is the key for accessing the attribute store and `<cpr>` is the CPR No. for collecting of the end-user data. The reply is passed as a single JSON data structure:
```
{
        "id":"4f5df82ce88f87.13862999",
        "version":"1.0",
        "userid":"8888888888",
        "attributes": {
                      "affiliation": {
                                     <staff | student>@<organisation id>,
                                     ...
                       },
                       <attribute name>: {...},
                       ...,
        },
        "status":{"code":0}
}
```
  * 'id' is a unique id for the JSON reply
  * 'version' describes the version used of JSON API
  * 'userid' contains a local unique id for the requested end-user
  * 'attributes' contains the end-user's information, The 'attributes' element can be this empty list
  * An element (key) in 'attributes' can consists of the empty list as value
  * 'affiliation' element can have the value of 'NoAffiliation' if the actual end-user is not staff or student at Aarhus University or SB. If true the value will be 'stud' for student and 'staff' for staff at these two institutions. More precisely the set of values can be 'stud|staff@au.dk' OR 'staff@statsbiblioteket.dk'
  * 'status' can have 'code' set to '0' or '1'. If '1' then an error has occured and an extra element can be found in the list. This element is called 'message' and will have the value of a description of the error

#### SB security module ####
  * A simple IP based authorisation is used and data is exchanged through SSL


---


## ~~Filmstriben (DBC)~~ ##
NOT TO BE IMPLEMENTED, DBC DROPPED OUT


---


## ~~VIP-Basen (DBC)~~ ##
NOT TO BE IMPLEMENTED, DBC DROPPED OUT