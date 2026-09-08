CREATE VIEW vista_numero_aziende AS

SELECT

    COUNT(*) AS numero_aziende

FROM azienda;


CREATE VIEW vista_numero_revisori AS

SELECT

    COUNT(*) AS numero_revisori

FROM revisore_esg;


CREATE OR REPLACE VIEW vista_affidabilita_aziende AS

SELECT
    a.id_azienda,
    a.nome,

    COUNT(
        CASE
            WHEN b.stato IN ('approvato', 'respinto')
            AND EXISTS (
                SELECT 1
                FROM giudizio_revisore g
                WHERE g.id_bilancio = b.id_bilancio
            )
            AND NOT EXISTS (
                SELECT 1
                FROM giudizio_revisore g
                WHERE g.id_bilancio = b.id_bilancio
                AND g.esito <> 'approvazione'
            )
            THEN 1
        END
    ) AS bilanci_approvati,

    COUNT(
        CASE
            WHEN b.stato IN ('approvato', 'respinto')
            THEN 1
        END
    ) AS bilanci_conclusi,

    ROUND(
        COUNT(
            CASE
                WHEN b.stato IN ('approvato', 'respinto')
                AND EXISTS (
                    SELECT 1
                    FROM giudizio_revisore g
                    WHERE g.id_bilancio = b.id_bilancio
                )
                AND NOT EXISTS (
                    SELECT 1
                    FROM giudizio_revisore g
                    WHERE g.id_bilancio = b.id_bilancio
                    AND g.esito <> 'approvazione'
                )
                THEN 1
            END
        ) * 100.0
        /
        NULLIF(
            COUNT(
                CASE
                    WHEN b.stato IN ('approvato', 'respinto')
                    THEN 1
                END
            ),
            0
        ),
        2
    ) AS percentuale_affidabilita

FROM azienda a

LEFT JOIN bilancio b
ON a.id_azienda = b.id_azienda

GROUP BY
    a.id_azienda,
    a.nome;


CREATE VIEW vista_classifica_bilanci AS

SELECT

    b.id_bilancio,

    a.nome AS azienda,

    COUNT(vi.id_indicatore) AS totale_indicatori_esg

FROM bilancio b

JOIN azienda a
ON b.id_azienda = a.id_azienda

LEFT JOIN voce_bilancio vb
ON b.id_bilancio = vb.id_bilancio

LEFT JOIN voce_indicatore vi
ON vb.id_voce_bilancio = vi.id_voce_bilancio

GROUP BY

    b.id_bilancio,
    a.nome

ORDER BY totale_indicatori_esg DESC;