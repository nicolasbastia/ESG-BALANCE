DELIMITER $$

CREATE TRIGGER trg_bilancio_in_revisione

AFTER INSERT
ON assegnazione_revisore

FOR EACH ROW

BEGIN

    UPDATE bilancio

    SET stato = 'in revisione'

    WHERE id_bilancio = NEW.id_bilancio;

END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER trg_stato_finale_bilancio

AFTER INSERT
ON giudizio_revisore

FOR EACH ROW

BEGIN

    DECLARE totale_revisori INT;
    DECLARE totale_giudizi INT;
    DECLARE respingimenti INT;

    /*
        Numero revisori assegnati
    */
    SELECT COUNT(*)

    INTO totale_revisori

    FROM assegnazione_revisore

    WHERE id_bilancio = NEW.id_bilancio;

    /*
        Numero giudizi inseriti
    */
    SELECT COUNT(*)

    INTO totale_giudizi

    FROM giudizio_revisore

    WHERE id_bilancio = NEW.id_bilancio;

    /*
        Numero respingimenti
    */
    SELECT COUNT(*)

    INTO respingimenti

    FROM giudizio_revisore

    WHERE id_bilancio = NEW.id_bilancio

    AND esito = 'respingimento';

    /*
        Tutti i revisori hanno votato
    */
    IF totale_revisori = totale_giudizi THEN

        /*
            Almeno un respingimento
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