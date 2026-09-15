-- =====================================================
-- DATI INIZIALI PER IL TEST DELL'APPLICAZIONE
-- =====================================================

-- Account amministratore di test
-- Username: admin
-- Password: admin123

INSERT IGNORE INTO utente (
    username,
    password,
    codice_fiscale,
    data_nascita,
    luogo_nascita,
    ruolo
)
VALUES (
    'admin',
    '$2y$10$IduoX.y4ZoHjRzJ8z/bmDuUDWavT4ar7G4JgyYyEzeqKONUjc/UBK',
    'TSTDMN90A01A944X',
    '1990-01-01',
    'Bologna',
    'amministratore'
);