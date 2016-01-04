# Use cases for netlydbog.dk #

## Use Case 1 ##
En bruger går ind på netlydbog.dk og logger ind med det samme. Hun vælger at logge ind med NemID.
I forbindelse med indlogningen hentes informationer om hvilken kommune brugeren er tilknyttet.

Hun søger den ønskede titel frem og klikker herefter på ”Hent”. Filen downloades til hendes computer.
Hun beslutter sig for også at downloade bind 2 i serien. Hun søger titlen frem og klikker på ”Hent”. Filen overføres til hendes computer.

### Det sker ###
Ved indlogning sendes cpr-nr. fra NemID-login retur til attribut collectoren, som ud fra cpr-nr. har låneren registreret som bosiddende i x-købing kommune. Hun udstyres med rettigheder i forhold til x-købing kommune, hvorfor hun ikke selv angive hvilket bibliotek hun kommer fra.

Biblioteket registreres for udlånet i administrationsystemet.

**Man skal være bosiddende i den kommune biblioteket ligger i for at kunne låne netlydbøger.**


## Use Case 2 ##
En bruger browser rundt på netlydbog.dk for at finde en lydbog han ønsker at høre til og fra arbejde. Da han finder den ønskede titlen klikker han på ”Hent” for at downloade lydbogen til sin computer.

Da brugeren ikke er logget ind, videresendes han til login siden for indlogning.
Han vælger login med nemID. I forbindelse med indlogning hentes information om brugeres bopælskom-mune og da kommune har tegnet abonnement på netlydbog.dk – få brugeren lov til at downloade titlen.

### Det sker ###
Ved indlogning sendes cpr-nr. fra NemID-login retur til attribut-collectoren, som ud fra cpr-nr. tjekker låneren. Cpr-nummet findes kun som biblioteksbruger på Statsbiblioteket. Da Statsbiblioteket ikke har tegnet abonnement på netlydbog.dk forespørges de lokale bibliotekssystemer i folkebiblioteker i Midtjylland om hvor brugeren hører til. Attribut-collectoren får svar fra Silkeborg Bibliotek og cpr-nr. tilknyttes Silkeborg Bibliotek + Silkeborg Kommune.

Der sendes besked til Silkeborg Bibliotek om at oprette låneren som bruger (digital bruger). Hvordan Silkeborg Bibliotek håndtere dette er ikke en del af usecasen.

## Use Case 3 ##
Et barn browser rundt på netlydbog.dk for at finde en lydbog han ønsker at høre på vej til skole. Da han finder den ønskede titlen klikker han på ”Lyt” for at streame lydbogen fra hans iPhone.

Da han ikke er logget ind, videresendes han til login siden. Da han ikke har et NemID, vælger han at logge ind med hans lånekort. Han skal selv vælge hvilket bibliotek han er tilknyttet.

### Det sker ###
Ved indlogning tjekkes at brugeren er oprettet som låner på det valgte bibliotek.