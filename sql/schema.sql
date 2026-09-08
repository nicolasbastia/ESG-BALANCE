CREATE TABLE utente (

    id_utente INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,

    codice_fiscale VARCHAR(16) UNIQUE NOT NULL,

    data_nascita DATE NOT NULL,

    luogo_nascita VARCHAR(100) NOT NULL,

    ruolo ENUM(
        'amministratore',
        'revisore',
        'responsabile'
    ) NOT NULL

);

CREATE TABLE email_utente (

    id_email INT AUTO_INCREMENT PRIMARY KEY,

    id_utente INT NOT NULL,

    email VARCHAR(100) NOT NULL,

    FOREIGN KEY (id_utente)
    REFERENCES utente(id_utente)
    ON DELETE CASCADE

);

CREATE TABLE revisore_esg (

    id_utente INT PRIMARY KEY,

    numero_revisioni INT DEFAULT 0,

    indice_affidabilita DECIMAL(5,2),

    FOREIGN KEY (id_utente)
    REFERENCES utente(id_utente)
    ON DELETE CASCADE

);

CREATE TABLE responsabile_aziendale (

    id_utente INT PRIMARY KEY,

    cv_pdf VARCHAR(255),

    FOREIGN KEY (id_utente)
    REFERENCES utente(id_utente)
    ON DELETE CASCADE

);

CREATE TABLE competenza (

    id_competenza INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) UNIQUE NOT NULL

);

CREATE TABLE competenza_revisore (

    id_utente INT,

    id_competenza INT,

    livello INT CHECK (
        livello BETWEEN 0 AND 5
    ),

    PRIMARY KEY (
        id_utente,
        id_competenza
    ),

    FOREIGN KEY (id_utente)
    REFERENCES revisore_esg(id_utente)
    ON DELETE CASCADE,

    FOREIGN KEY (id_competenza)
    REFERENCES competenza(id_competenza)
    ON DELETE CASCADE

);

CREATE TABLE azienda (

    id_azienda INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,

    ragione_sociale VARCHAR(150) UNIQUE NOT NULL,

    partita_iva VARCHAR(20) UNIQUE NOT NULL,

    settore VARCHAR(100),

    numero_dipendenti INT,

    logo VARCHAR(255),

    nr_bilanci INT DEFAULT 0,

    id_responsabile INT NOT NULL,

    FOREIGN KEY (id_responsabile)
    REFERENCES responsabile_aziendale(id_utente)

);

CREATE TABLE voce_template (

    id_voce INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) UNIQUE NOT NULL,

    descrizione TEXT,

    id_amministratore INT NOT NULL,

    FOREIGN KEY (id_amministratore)
    REFERENCES utente(id_utente)

);

CREATE TABLE bilancio (

    id_bilancio INT AUTO_INCREMENT PRIMARY KEY,

    id_azienda INT NOT NULL,

    data_creazione DATE NOT NULL,

    stato ENUM(
        'bozza',
        'in revisione',
        'approvato',
        'respinto'
    ) DEFAULT 'bozza',

    FOREIGN KEY (id_azienda)
    REFERENCES azienda(id_azienda)
    ON DELETE CASCADE

);

CREATE TABLE voce_bilancio (

    id_voce_bilancio INT AUTO_INCREMENT PRIMARY KEY,

    id_bilancio INT NOT NULL,

    id_voce INT NOT NULL,

    valore DECIMAL(15,2) NOT NULL,

    FOREIGN KEY (id_bilancio)
    REFERENCES bilancio(id_bilancio)
    ON DELETE CASCADE,

    FOREIGN KEY (id_voce)
    REFERENCES voce_template(id_voce)

);

CREATE TABLE indicatore_esg (

    id_indicatore INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) UNIQUE NOT NULL,

    immagine VARCHAR(255),

    rilevanza INT CHECK (
        rilevanza BETWEEN 0 AND 10
    )

);

CREATE TABLE indicatore_ambientale (

    id_indicatore INT PRIMARY KEY,

    codice_normativa VARCHAR(100) NOT NULL,

    FOREIGN KEY (id_indicatore)
    REFERENCES indicatore_esg(id_indicatore)
    ON DELETE CASCADE

);

CREATE TABLE indicatore_sociale (

    id_indicatore INT PRIMARY KEY,

    ambito_sociale VARCHAR(150) NOT NULL,

    frequenza_rilevazione VARCHAR(100) NOT NULL,

    FOREIGN KEY (id_indicatore)
    REFERENCES indicatore_esg(id_indicatore)
    ON DELETE CASCADE

);

CREATE TABLE voce_indicatore (

    id_voce_bilancio INT,

    id_indicatore INT,

    valore_indicatore DECIMAL(15,2),

    fonte VARCHAR(255),

    data_rilevazione DATE,

    PRIMARY KEY (
        id_voce_bilancio,
        id_indicatore
    ),

    FOREIGN KEY (id_voce_bilancio)
    REFERENCES voce_bilancio(id_voce_bilancio)
    ON DELETE CASCADE,

    FOREIGN KEY (id_indicatore)
    REFERENCES indicatore_esg(id_indicatore)
    ON DELETE CASCADE

);


CREATE TABLE revisione (

    id_revisione INT AUTO_INCREMENT PRIMARY KEY,

    id_bilancio INT NOT NULL,

    id_revisore INT NOT NULL,

    data_assegnazione TIMESTAMP
    NOT NULL
    DEFAULT CURRENT_TIMESTAMP,

    stato VARCHAR(50)
    DEFAULT 'assegnata',

    UNIQUE (
        id_bilancio,
        id_revisore
    ),

    FOREIGN KEY (id_bilancio)
    REFERENCES bilancio(id_bilancio)
    ON DELETE CASCADE,

    FOREIGN KEY (id_revisore)
    REFERENCES revisore_esg(id_utente)
    ON DELETE CASCADE

);

CREATE TABLE nota_revisore (

    id_nota INT AUTO_INCREMENT PRIMARY KEY,

    id_revisore INT NOT NULL,

    id_voce_bilancio INT NOT NULL,

    data_nota DATE NOT NULL,

    testo TEXT NOT NULL,

    FOREIGN KEY (id_revisore)
    REFERENCES revisore_esg(id_utente)
    ON DELETE CASCADE,

    FOREIGN KEY (id_voce_bilancio)
    REFERENCES voce_bilancio(id_voce_bilancio)
    ON DELETE CASCADE

);

CREATE TABLE giudizio_revisore (

    id_giudizio INT AUTO_INCREMENT PRIMARY KEY,

    id_bilancio INT NOT NULL,

    id_revisore INT NOT NULL,

    esito ENUM(
        'approvazione',
        'approvazione con rilievi',
        'respingimento'
    ) NOT NULL,

    data_giudizio DATE NOT NULL,

    rilievi TEXT,

    UNIQUE (
    id_bilancio,
    id_revisore
),

    FOREIGN KEY (id_bilancio)
    REFERENCES bilancio(id_bilancio)
    ON DELETE CASCADE,

    FOREIGN KEY (id_revisore)
    REFERENCES revisore_esg(id_utente)
    ON DELETE CASCADE

);

