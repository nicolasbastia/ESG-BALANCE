/* REGISTRAZIONE UTENTE */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_registra_utente$$

CREATE PROCEDURE sp_registra_utente(

    IN p_username VARCHAR(50),
    IN p_password VARCHAR(255),
    IN p_codice_fiscale VARCHAR(16),
    IN p_data_nascita DATE,
    IN p_luogo_nascita VARCHAR(100),
    IN p_ruolo VARCHAR(20)

)

BEGIN

    INSERT INTO utente(

        username,
        password,
        codice_fiscale,
        data_nascita,
        luogo_nascita,
        ruolo

    )

    VALUES(

        p_username,
        p_password,
        p_codice_fiscale,
        p_data_nascita,
        p_luogo_nascita,
        p_ruolo

    );

    SELECT LAST_INSERT_ID() AS id_utente;

END$$

DELIMITER ;



DELIMITER $$

DROP PROCEDURE IF EXISTS sp_aggiungi_email_utente$$

CREATE PROCEDURE sp_aggiungi_email_utente(
    IN p_id_utente INT,
    IN p_email VARCHAR(100)
)
BEGIN

    INSERT INTO email_utente(
        id_utente,
        email
    )
    VALUES(
        p_id_utente,
        p_email
    );

END$$


DROP PROCEDURE IF EXISTS sp_crea_revisore_esg$$

CREATE PROCEDURE sp_crea_revisore_esg(
    IN p_id_utente INT
)
BEGIN

    INSERT INTO revisore_esg(
        id_utente
    )
    VALUES(
        p_id_utente
    );

END$$


DROP PROCEDURE IF EXISTS sp_crea_responsabile_aziendale$$

CREATE PROCEDURE sp_crea_responsabile_aziendale(
    IN p_id_utente INT
)
BEGIN

    INSERT INTO responsabile_aziendale(
        id_utente
    )
    VALUES(
        p_id_utente
    );

END$$

DELIMITER ;



/* REGISTRAZIONE AZIENDE */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_registra_azienda$$

CREATE PROCEDURE sp_registra_azienda(

    IN p_nome VARCHAR(100),
    IN p_ragione_sociale VARCHAR(150),
    IN p_partita_iva VARCHAR(20),
    IN p_settore VARCHAR(100),
    IN p_numero_dipendenti INT,
    IN p_logo VARCHAR(255),
    IN p_id_responsabile INT

)

BEGIN

    INSERT INTO azienda(

        nome,
        ragione_sociale,
        partita_iva,
        settore,
        numero_dipendenti,
        logo,
        id_responsabile

    )

    VALUES(

        p_nome,
        p_ragione_sociale,
        p_partita_iva,
        p_settore,
        p_numero_dipendenti,
        p_logo,
        p_id_responsabile

    );

END$$

DELIMITER ;

/* ELIMINA AZIENDE*/

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_elimina_azienda$$

CREATE PROCEDURE sp_elimina_azienda(
    IN p_id_azienda INT
)
BEGIN

    DELETE FROM azienda
    WHERE id_azienda = p_id_azienda;

END$$

DELIMITER ;


/* CREAZIONE BILANCIO */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_crea_bilancio$$

CREATE PROCEDURE sp_crea_bilancio(

    IN p_id_azienda INT,
    IN p_data_creazione DATE

)

BEGIN

    INSERT INTO bilancio(

        id_azienda,
        data_creazione

    )

    VALUES(

        p_id_azienda,
        p_data_creazione

    );

    /* Aggiorna ridondanza nr_bilanci */

    UPDATE azienda

    SET nr_bilanci = nr_bilanci + 1

    WHERE id_azienda = p_id_azienda;

END$$

DELIMITER ;

/* ASSEGNAZIONE REVISORE*/

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_assegna_revisore$$

CREATE PROCEDURE sp_assegna_revisore(

    IN p_id_bilancio INT,
    IN p_id_revisore INT,
    IN p_data DATE

)

BEGIN

    INSERT INTO revisione(

        id_bilancio,
        id_revisore,
        data_assegnazione

    )

    VALUES(

        p_id_bilancio,
        p_id_revisore,
        p_data

    );

END$$

DELIMITER ;

/* INSERIMENTO GIUDIZIO E NOTE*/

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_inserisci_giudizio;

CREATE PROCEDURE sp_inserisci_giudizio(

    IN p_id_bilancio INT,
    IN p_id_revisore INT,
    IN p_esito VARCHAR(50),
    IN p_data DATE,
    IN p_rilievi TEXT

)

BEGIN

    IF NOT EXISTS (
        SELECT 1
        FROM revisione
        WHERE id_bilancio = p_id_bilancio
          AND id_revisore = p_id_revisore
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT =
            'Il revisore non è assegnato a questo bilancio';
    END IF;

    INSERT INTO giudizio_revisore (
        id_bilancio,
        id_revisore,
        esito,
        data_giudizio,
        rilievi

    )

    VALUES (
        p_id_bilancio,
        p_id_revisore,
        p_esito,
        p_data,
        p_rilievi

    );

END$$

DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_inserisci_nota$$

CREATE PROCEDURE sp_inserisci_nota(

    IN p_id_revisore INT,
    IN p_id_voce_bilancio INT,
    IN p_data DATE,
    IN p_testo TEXT

)

BEGIN

    INSERT INTO nota_revisore(

        id_revisore,
        id_voce_bilancio,
        data_nota,
        testo

    )

    VALUES(

        p_id_revisore,
        p_id_voce_bilancio,
        p_data,
        p_testo

    );

END$$

DELIMITER ;


/* AFFIDABILITÁ DEL REVISORE */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_aggiorna_affidabilita_revisore$$

CREATE PROCEDURE sp_aggiorna_affidabilita_revisore(

    IN p_id_revisore INT

)

BEGIN

    DECLARE v_revisioni_assegnate INT DEFAULT 0;

    DECLARE v_revisioni_concluse INT DEFAULT 0;

    DECLARE v_indice DECIMAL(5,2) DEFAULT 0;



    SELECT COUNT(DISTINCT id_bilancio)

    INTO v_revisioni_assegnate

    FROM revisione

    WHERE id_revisore = p_id_revisore;



    SELECT COUNT(DISTINCT id_bilancio)

    INTO v_revisioni_concluse

    FROM giudizio_revisore

    WHERE id_revisore = p_id_revisore;



    IF v_revisioni_assegnate > 0 THEN

        SET v_indice =

            (
                v_revisioni_concluse
                /
                v_revisioni_assegnate
            ) * 100;

    ELSE

        SET v_indice = 0;

    END IF;



    UPDATE revisore_esg

    SET

        numero_revisioni = v_revisioni_concluse,

        indice_affidabilita = v_indice

    WHERE id_utente = p_id_revisore;


END$$

DELIMITER ;

/* ELIMINA BILANCIO */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_elimina_bilancio$$

CREATE PROCEDURE sp_elimina_bilancio(
    IN p_id_bilancio INT
)
BEGIN

    DECLARE v_id_azienda INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;


    SELECT id_azienda
    INTO v_id_azienda
    FROM bilancio
    WHERE id_bilancio = p_id_bilancio;


    DELETE FROM giudizio_revisore
    WHERE id_bilancio = p_id_bilancio;


    DELETE nr
    FROM nota_revisore nr
    JOIN voce_bilancio vb
        ON nr.id_voce_bilancio = vb.id_voce_bilancio
    WHERE vb.id_bilancio = p_id_bilancio;


    DELETE FROM revisione
    WHERE id_bilancio = p_id_bilancio;


    DELETE vi
    FROM voce_indicatore vi
    JOIN voce_bilancio vb
        ON vi.id_voce_bilancio = vb.id_voce_bilancio
    WHERE vb.id_bilancio = p_id_bilancio;


    DELETE FROM voce_bilancio
    WHERE id_bilancio = p_id_bilancio;


    DELETE FROM bilancio
    WHERE id_bilancio = p_id_bilancio;

    
    /* Ricalcola nr_bilanci dell'azienda*/

    UPDATE azienda
    SET nr_bilanci = (
        SELECT COUNT(*)
        FROM bilancio
        WHERE id_azienda = v_id_azienda
    )
    WHERE id_azienda = v_id_azienda;

    COMMIT;

END$$

DELIMITER ;

/* COMPETENZE REVISORE */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_aggiungi_competenza_revisore$$

CREATE PROCEDURE sp_aggiungi_competenza_revisore(
    IN p_id_utente INT,
    IN p_id_competenza INT,
    IN p_livello INT
)
BEGIN

    IF p_livello < 0 OR p_livello > 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Il livello deve essere compreso tra 0 e 5';
    END IF;

    INSERT INTO competenza_revisore(
        id_utente,
        id_competenza,
        livello
    )
    VALUES(
        p_id_utente,
        p_id_competenza,
        p_livello
    );

END$$


DROP PROCEDURE IF EXISTS sp_modifica_competenza_revisore$$

CREATE PROCEDURE sp_modifica_competenza_revisore(
    IN p_id_utente INT,
    IN p_id_competenza INT,
    IN p_livello INT
)
BEGIN

    IF p_livello < 0 OR p_livello > 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Il livello deve essere compreso tra 0 e 5';
    END IF;

    UPDATE competenza_revisore
    SET livello = p_livello
    WHERE id_utente = p_id_utente
    AND id_competenza = p_id_competenza;

END$$


DROP PROCEDURE IF EXISTS sp_elimina_competenza_revisore$$

CREATE PROCEDURE sp_elimina_competenza_revisore(
    IN p_id_utente INT,
    IN p_id_competenza INT
)
BEGIN

    DELETE FROM competenza_revisore
    WHERE id_utente = p_id_utente
    AND id_competenza = p_id_competenza;

END$$

DELIMITER ;

/* OPERAZIONI CV RESPONSABILE */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_aggiorna_cv_responsabile$$

CREATE PROCEDURE sp_aggiorna_cv_responsabile(
    IN p_id_utente INT,
    IN p_cv_pdf VARCHAR(255)
)
BEGIN

    UPDATE responsabile_aziendale
    SET cv_pdf = p_cv_pdf
    WHERE id_utente = p_id_utente;

END$$


DROP PROCEDURE IF EXISTS sp_elimina_cv_responsabile$$

CREATE PROCEDURE sp_elimina_cv_responsabile(
    IN p_id_utente INT
)
BEGIN

    UPDATE responsabile_aziendale
    SET cv_pdf = NULL
    WHERE id_utente = p_id_utente;

END$$

DELIMITER ;

/* SALVATAGGIO VOCE BILANCIO*/

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_salva_voce_bilancio$$

CREATE PROCEDURE sp_salva_voce_bilancio(
    IN p_id_bilancio INT,
    IN p_id_voce INT,
    IN p_valore DECIMAL(15,2)
)
BEGIN

    IF EXISTS (
        SELECT 1
        FROM voce_bilancio
        WHERE id_bilancio = p_id_bilancio
        AND id_voce = p_id_voce
    ) THEN

        UPDATE voce_bilancio
        SET valore = p_valore
        WHERE id_bilancio = p_id_bilancio
        AND id_voce = p_id_voce;

    ELSE

        INSERT INTO voce_bilancio (
            id_bilancio,
            id_voce,
            valore
        )
        VALUES (
            p_id_bilancio,
            p_id_voce,
            p_valore
        );

    END IF;

END$$

DELIMITER ;


/* CREAZIONE/ELIMINAZIONE VOCE TEMPLATE */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_crea_voce_template$$

CREATE PROCEDURE sp_crea_voce_template(
    IN p_nome VARCHAR(100),
    IN p_descrizione TEXT,
    IN p_id_amministratore INT
)
BEGIN

    INSERT INTO voce_template(
        nome,
        descrizione,
        id_amministratore
    )
    VALUES(
        p_nome,
        p_descrizione,
        p_id_amministratore
    );

END$$


DROP PROCEDURE IF EXISTS sp_elimina_voce_template$$

CREATE PROCEDURE sp_elimina_voce_template(
    IN p_id_voce INT
)
BEGIN

    DELETE FROM voce_template
    WHERE id_voce = p_id_voce;

END$$

DELIMITER ;



/* CREAZIONE/ELIMINAZIONE INDICATORE ESG */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_crea_indicatore_esg$$

CREATE PROCEDURE sp_crea_indicatore_esg(
    IN p_nome VARCHAR(100),
    IN p_immagine VARCHAR(255),
    IN p_rilevanza INT,
    IN p_categoria VARCHAR(20),
    IN p_codice_normativa VARCHAR(100),
    IN p_ambito_sociale VARCHAR(150),
    IN p_frequenza_rilevazione VARCHAR(100)
)
BEGIN

    DECLARE v_id_indicatore INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    IF p_rilevanza < 0 OR p_rilevanza > 10 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La rilevanza deve essere compresa tra 0 e 10';
    END IF;

    IF p_categoria NOT IN ('ambientale', 'sociale', 'nessuna') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Categoria indicatore non valida';
    END IF;

    START TRANSACTION;

    INSERT INTO indicatore_esg(
        nome,
        immagine,
        rilevanza
    )
    VALUES(
        p_nome,
        p_immagine,
        p_rilevanza
    );

    SET v_id_indicatore = LAST_INSERT_ID();

    IF p_categoria = 'ambientale' THEN

        INSERT INTO indicatore_ambientale(
            id_indicatore,
            codice_normativa
        )
        VALUES(
            v_id_indicatore,
            p_codice_normativa
        );

    ELSEIF p_categoria = 'sociale' THEN

        INSERT INTO indicatore_sociale(
            id_indicatore,
            ambito_sociale,
            frequenza_rilevazione
        )
        VALUES(
            v_id_indicatore,
            p_ambito_sociale,
            p_frequenza_rilevazione
        );

    END IF;

    COMMIT;

END$$


DROP PROCEDURE IF EXISTS sp_elimina_indicatore_esg$$

CREATE PROCEDURE sp_elimina_indicatore_esg(
    IN p_id_indicatore INT
)
BEGIN

    DELETE FROM indicatore_esg
    WHERE id_indicatore = p_id_indicatore;

END$$

DELIMITER ;





/*COLLEGAMENTO INDICATORE ESG */


DELIMITER $$

DROP PROCEDURE IF EXISTS sp_collega_indicatore_voce$$

CREATE PROCEDURE sp_collega_indicatore_voce(
    IN p_id_voce_bilancio INT,
    IN p_id_indicatore INT,
    IN p_valore_indicatore DECIMAL(15,2),
    IN p_fonte VARCHAR(255),
    IN p_data_rilevazione DATE
)
BEGIN

    INSERT INTO voce_indicatore(
        id_voce_bilancio,
        id_indicatore,
        valore_indicatore,
        fonte,
        data_rilevazione
    )
    VALUES(
        p_id_voce_bilancio,
        p_id_indicatore,
        p_valore_indicatore,
        p_fonte,
        p_data_rilevazione
    );

END$$

DELIMITER ;

