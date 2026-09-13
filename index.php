<?php

session_start();

if(!isset($_SESSION['utente'])) {

    header('Location: /esg-balance/login.php');
    exit;
}

$utente = $_SESSION['utente'];

include __DIR__ . '/views/partials/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>

            Benvenuto
            <?= htmlspecialchars($utente['username']) ?>

        </h1>

        <p class="text-muted">

            Ruolo:

            <strong>

                <?= htmlspecialchars(
                    ucfirst($utente['ruolo'])
                ) ?>

            </strong>

        </p>

    </div>

    <div>

        <a
            href="/esg-balance/logout.php"
            class="btn btn-danger"
        >

            Logout

        </a>

    </div>

</div>


<hr>


<div class="row">


<?php

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

if($utente['ruolo'] === 'amministratore') :

?>

    <!-- INDICATORI ESG -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Indicatori ESG
                </h5>

                <p class="card-text">
                    Gestione completa degli indicatori ESG.
                </p>

                <a
                    href="/esg-balance/indicatori.php"
                    class="btn btn-primary"
                >

                    Gestisci

                </a>

            </div>

        </div>

    </div>


    <!-- TEMPLATE BILANCIO -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Template Bilancio
                </h5>

                <p class="card-text">
                    Gestione voci contabili del template.
                </p>

                <a
                    href="/esg-balance/template.php"
                    class="btn btn-primary"
                >

                    Gestisci

                </a>

            </div>

        </div>

    </div>


    <!-- REVISIONI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Revisioni ESG
                </h5>

                <p class="card-text">
                    Assegnazione revisori ai bilanci.
                </p>

                <a
                    href="/esg-balance/revisioni.php"
                    class="btn btn-primary"
                >

                    Gestisci

                </a>

            </div>

        </div>

    </div>


    <!-- UTENTI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Competenze e CV utenti
                </h5>

                <p class="card-text">
                    Visualizza le competenze dei revisori e i CV dei responsabili aziendali.
                </p>

                <a
                    href="/esg-balance/admin_utenti.php"
                    class="btn btn-primary"
                >

                    Visualizza

                </a>

            </div>

        </div>

    </div>

<?php endif; ?>


<?php

/*
|--------------------------------------------------------------------------
| DASHBOARD RESPONSABILE
|--------------------------------------------------------------------------
*/

if($utente['ruolo'] === 'responsabile') :

?>

    <!-- PROFILO RESPONSABILE -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Il mio profilo
                </h5>

                <p class="card-text">
                    Gestisci il Curriculum Vitae in formato PDF.
                </p>

                <a
                    href="/esg-balance/responsabile.php"
                    class="btn btn-primary"
                >

                    Gestisci CV

                </a>

            </div>

        </div>

    </div>


    <!-- AZIENDE -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Aziende
                </h5>

                <p class="card-text">
                    Gestione aziende registrate.
                </p>

                <a
                    href="/esg-balance/aziende.php"
                    class="btn btn-primary"
                >

                    Gestisci

                </a>

            </div>

        </div>

    </div>


    <!-- BILANCI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Bilanci
                </h5>

                <p class="card-text">
                    Gestione bilanci aziendali ESG.
                </p>

                <a
                    href="/esg-balance/bilanci.php"
                    class="btn btn-primary"
                >

                    Gestisci

                </a>

            </div>

        </div>

    </div>

<?php endif; ?>


<?php

/*
|--------------------------------------------------------------------------
| DASHBOARD REVISORE
|--------------------------------------------------------------------------
*/

if($utente['ruolo'] === 'revisore') :

?>

    <!-- COMPETENZE -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Le mie competenze
                </h5>

                <p class="card-text">
                    Gestisci le competenze ESG e i relativi livelli.
                </p>

                <a
                    href="/esg-balance/competenza.php"
                    class="btn btn-success"
                >

                    Gestisci competenze

                </a>

            </div>

        </div>

    </div>


    <!-- NUMERO REVISIONI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Revisioni effettuate
                </h5>

                <h2>

                    <?= htmlspecialchars(
                        (string) ($utente['numero_revisioni'] ?? 0)
                    ) ?>

                </h2>

            </div>

        </div>

    </div>


    <!-- INDICE AFFIDABILITA -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Indice di affidabilità
                </h5>

                <h2>

                    <?= htmlspecialchars(
                        (string) ($utente['indice_affidabilita'] ?? 0)
                    ) ?>%

                </h2>

            </div>

        </div>

    </div>


    <!-- REVISIONI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Revisioni ESG
                </h5>

                <p class="card-text">
                    Gestione note e giudizi revisori.
                </p>

                <a
                    href="/esg-balance/revisioni_revisore.php"
                    class="btn btn-primary"
                >

                    Apri Revisioni

                </a>

            </div>

        </div>

    </div>

<?php endif; ?>


<!-- STATISTICHE VISIBILI A TUTTI -->

<div class="col-md-4 mb-4">

    <div class="card shadow-sm h-100">

        <div class="card-body">

            <h5 class="card-title">
                Statistiche
            </h5>

            <p class="card-text">
                Visualizza le statistiche generali della piattaforma.
            </p>

            <a
                href="/esg-balance/statistiche.php"
                class="btn btn-primary"
            >

                Visualizza

            </a>

        </div>

    </div>

</div>


</div>


<?php

include __DIR__ . '/views/partials/footer.php';

?>