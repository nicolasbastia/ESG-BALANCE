DELIMITER $$

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

END$$

DELIMITER ;

DELIMITER $$

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

DELIMITER $$

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

    /*
        Aggiorna ridondanza nr_bilanci
    */

    UPDATE azienda

    SET nr_bilanci = nr_bilanci + 1

    WHERE id_azienda = p_id_azienda;

END$$

DELIMITER ;

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

DELIMITER $$

CREATE PROCEDURE sp_inserisci_giudizio(

    IN p_id_bilancio INT,
    IN p_id_revisore INT,
    IN p_esito VARCHAR(50),
    IN p_data DATE,
    IN p_rilievi TEXT

)

BEGIN

    INSERT INTO giudizio_revisore(

        id_bilancio,
        id_revisore,
        esito,
        data_giudizio,
        rilievi

    )

    VALUES(

        p_id_bilancio,
        p_id_revisore,
        p_esito,
        p_data,
        p_rilievi

    );

END$$

DELIMITER ;

DELIMITER $$

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


/* STORED PROCEDURE PER INDICE AFFIDABILITÁ DEL REVISORE*/

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_aggiorna_affidabilita_revisore$$

CREATE PROCEDURE sp_aggiorna_affidabilita_revisore(

    IN p_id_revisore INT

)

BEGIN

    DECLARE v_revisioni_assegnate INT DEFAULT 0;

    DECLARE v_revisioni_concluse INT DEFAULT 0;

    DECLARE v_indice DECIMAL(5,2) DEFAULT 0;


    /*
    |--------------------------------------------------------------------------
    | CONTA REVISIONI ASSEGNATE
    |--------------------------------------------------------------------------
    */

    SELECT COUNT(DISTINCT id_bilancio)

    INTO v_revisioni_assegnate

    FROM revisione

    WHERE id_revisore = p_id_revisore;


    /*
    |--------------------------------------------------------------------------
    | CONTA REVISIONI CONCLUSE
    |--------------------------------------------------------------------------
    */

    SELECT COUNT(DISTINCT id_bilancio)

    INTO v_revisioni_concluse

    FROM giudizio_revisore

    WHERE id_revisore = p_id_revisore;


    /*
    |--------------------------------------------------------------------------
    | CALCOLA INDICE
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | AGGIORNA REVISORE
    |--------------------------------------------------------------------------
    */

    UPDATE revisore_esg

    SET

        numero_revisioni = v_revisioni_concluse,

        indice_affidabilita = v_indice

    WHERE id_utente = p_id_revisore;


END$$

DELIMITER ;