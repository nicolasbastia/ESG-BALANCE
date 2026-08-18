CREATE VIEW vista_numero_aziende AS

SELECT

    COUNT(*) AS numero_aziende

FROM azienda;

CREATE VIEW vista_numero_revisori AS

SELECT

    COUNT(*) AS numero_revisori

FROM revisore_esg;

CREATE VIEW vista_affidabilita_aziende AS

SELECT

    a.id_azienda,

    a.nome,

    ROUND(

        (
            COUNT(

                CASE

                    WHEN g.esito = 'approvazione'

                    THEN 1

                END

            )

            * 100.0

        )

        / NULLIF(COUNT(g.id_giudizio), 0),

        2

    ) AS percentuale_affidabilita

FROM azienda a

LEFT JOIN bilancio b
ON a.id_azienda = b.id_azienda

LEFT JOIN giudizio_revisore g
ON b.id_bilancio = g.id_bilancio

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

