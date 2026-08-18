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

CREATE PROCEDURE sp_assegna_revisore(

    IN p_id_bilancio INT,
    IN p_id_revisore INT,
    IN p_data DATE

)

BEGIN

    INSERT INTO assegnazione_revisore(

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