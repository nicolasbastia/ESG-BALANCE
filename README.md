# ESG-BALANCE

ESG-BALANCE è un'applicazione Web sviluppata per la gestione e la revisione di bilanci ESG aziendali.

## Requisiti

Per eseguire l'applicazione sono necessari:

- PHP
- MySQL
- MongoDB
- Composer
- un server Web locale compatibile con PHP (ad esempio MAMP o XAMPP)

## Configurazione MySQL

L'applicazione utilizza un database MySQL denominato:

esg_balance

I parametri di connessione sono configurabili nel file:

config/db.php

La configurazione utilizzata durante lo sviluppo è:

Host: localhost  
Porta: 3307  
Database: esg_balance  
Utente: root  
Password: root  

Se la propria installazione MySQL utilizza parametri differenti, modificare opportunamente il file `config/db.php`.

## Creazione del database

Creare un database MySQL vuoto denominato:

esg_balance

Successivamente eseguire gli script presenti nella cartella `sql` nel seguente ordine:

1. `schema.sql`
2. `procedure.sql`
3. `trigger.sql`
4. `views.sql`
5. `seed.sql`

Il file `seed.sql` inserisce l'account amministratore di test necessario per il primo accesso all'applicazione.

## Credenziali amministratore di test

Username: admin  
Password: admin123

La password viene memorizzata nel database tramite hash e verificata dall'applicazione mediante le funzioni PHP dedicate alla gestione sicura delle password.

## Configurazione MongoDB

L'applicazione utilizza MongoDB per la registrazione degli eventi significativi che avvengono durante l'utilizzo della piattaforma.

La configurazione della connessione è contenuta nel file:

`config/mongo.php`

La configurazione utilizzata durante lo sviluppo è:

Host: localhost  
Porta: 27017  
Database: esg_balance_logs

Assicurarsi che il servizio MongoDB sia avviato prima di utilizzare le funzionalità dell'applicazione che effettuano il logging degli eventi.

## Logging degli eventi con MongoDB

Gli eventi significativi della piattaforma vengono registrati nel database MongoDB:

`esg_balance_logs`

all'interno della collezione:

`eventi`

Il logging è gestito dal file:

`config/logger.php`

Ogni documento della collezione `eventi` contiene:

- `evento`: descrizione testuale dell'operazione effettuata;
- `timestamp`: data e ora in cui l'evento è stato registrato.

La struttura di un documento MongoDB è quindi, ad esempio:

```javascript
{
    "_id": ObjectId("..."),
    "evento": "Creato nuovo bilancio ID 5 per l'azienda GreenTech",
    "timestamp": ISODate("2026-09-15T10:30:00Z")
}
```


## Dipendenze PHP

Le dipendenze PHP sono gestite tramite Composer.

Se necessario, dalla directory principale del progetto eseguire:

composer install

## Avvio dell'applicazione

Posizionare la cartella `ESG-BALANCE` nella directory servita dal proprio server Web locale.

Con la configurazione MAMP utilizzata durante lo sviluppo, la pagina di login è raggiungibile all'indirizzo:

http://localhost:8888/esg-balance/login.php

Per la configurazione XAMPP:

http://localhost/esg-balance/login.php

La porta e il percorso possono variare in base alla configurazione del server Web utilizzato.

## Accesso

Dopo aver configurato MySQL e MongoDB e importato gli script SQL, aprire la pagina di login e utilizzare l'account amministratore di test:

Username: admin  
Password: admin123