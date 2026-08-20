<?php

session_start();

if(!isset($_SESSION['utente'])) {

    header('Location: login.php');

    exit;
}

$utente = $_SESSION['utente'];

include __DIR__ . '/views/partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>

            Benvenuto
            <?= $utente['username'] ?>

        </h1>

        <p class="text-muted">

            Ruolo:
            <strong>
                <?= ucfirst($utente['ruolo']) ?>
            </strong>

        </p>

    </div>

    <div>

        <a
            href="logout.php"
            class="btn btn-danger"
        >

            Logout

        </a>

    </div>

</div>

<div class="alert alert-success">

    Login effettuato correttamente.

</div>

<hr>

<div class="row">

<?php

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

if($utente['ruolo'] == 'amministratore') :
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
                    href="indicatori.php"
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
                    href="template.php"
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
                    href="revisioni.php"
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
| DASHBOARD RESPONSABILE
|--------------------------------------------------------------------------
*/

if($utente['ruolo'] == 'responsabile') :
?>

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
                    href="aziende.php"
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
                    href="bilanci.php"
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

if($utente['ruolo'] == 'revisore') :
?>

    <!-- NUMERO REVISIONI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Revisioni effettuate
                </h5>

                <h2>
                    <?= $utente['numero_revisioni'] ?? 0 ?>
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
                    <?= $utente['indice_affidabilita'] ?? 0 ?>
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
                    href="revisioni_revisore.php"
                    class="btn btn-primary"
                >

                    Apri Revisioni

                </a>

            </div>

        </div>

    </div>

<?php endif; ?>

</div>

<?php
include __DIR__ . '/views/partials/footer.php';
?>