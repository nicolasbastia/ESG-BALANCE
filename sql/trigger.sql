DELIMITER $$

DROP TRIGGER IF EXISTS trg_bilancio_in_revisione$$

CREATE TRIGGER trg_bilancio_in_revisione

AFTER INSERT
ON revisione

FOR EACH ROW

BEGIN

    UPDATE bilancio

    SET stato = 'in revisione'

    WHERE id_bilancio = NEW.id_bilancio;

END$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS trg_stato_finale_bilancio$$

CREATE TRIGGER trg_stato_finale_bilancio

AFTER INSERT
ON giudizio_revisore

FOR EACH ROW

BEGIN

    DECLARE totale_revisori INT;
    DECLARE totale_giudizi INT;
    DECLARE respingimenti INT;

    /*
    |--------------------------------------------------------------------------
    | NUMERO REVISORI ASSEGNATI
    |--------------------------------------------------------------------------
    */

    SELECT COUNT(*)

    INTO totale_revisori

    FROM revisione

    WHERE id_bilancio = NEW.id_bilancio;


    /*
    |--------------------------------------------------------------------------
    | NUMERO GIUDIZI INSERITI
    |--------------------------------------------------------------------------
    */

    SELECT COUNT(DISTINCT id_revisore)

    INTO totale_giudizi

    FROM giudizio_revisore

    WHERE id_bilancio = NEW.id_bilancio;


    /*
    |--------------------------------------------------------------------------
    | NUMERO RESPINGIMENTI
    |--------------------------------------------------------------------------
    */

    SELECT COUNT(*)

    INTO respingimenti

    FROM giudizio_revisore

    WHERE id_bilancio = NEW.id_bilancio

    AND esito = 'respingimento';


    /*
    |--------------------------------------------------------------------------
    | TUTTI I REVISORI HANNO INSERITO IL GIUDIZIO
    |--------------------------------------------------------------------------
    */

    IF totale_revisori = totale_giudizi THEN

        /*
        |--------------------------------------------------------------------------
        | ALMENO UN RESPINGIMENTO
        |--------------------------------------------------------------------------
        */

        IF respingimenti > 0 THEN

            UPDATE bilancio

            SET stato = 'respinto'

            WHERE id_bilancio = NEW.id_bilancio;

        ELSE

            UPDATE bilancio

            SET stato = 'approvato'

            WHERE id_bilancio = NEW.id_bilancio;

        END IF;

    END IF;

END$$

DELIMITER ;