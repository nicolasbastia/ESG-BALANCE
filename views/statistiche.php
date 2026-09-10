<?php

include __DIR__ . '/partials/header.php';

$backUrl = '/ESG-BALANCE/index.php';
include __DIR__ . '/partials/back_button.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>Statistiche</h1>

        <p class="text-muted">
            Dati generali della piattaforma ESG-BALANCE
        </p>

    </div>

</div>


<div class="row">

    <!-- NUMERO AZIENDE -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Aziende registrate
                </h5>

                <h2>
                    <?= $numeroAziende['numero_aziende'] ?? 0 ?>
                </h2>

            </div>

        </div>

    </div>


    <!-- NUMERO REVISORI -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Revisori ESG registrati
                </h5>

                <h2>
                    <?= $numeroRevisori['numero_revisori'] ?? 0 ?>
                </h2>

            </div>

        </div>

    </div>


    <!-- AZIENDA PIU AFFIDABILE -->

    <div class="col-md-4 mb-4">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <h5 class="card-title">
                    Azienda più affidabile
                </h5>

                <?php if(isset($aziendaPiuAffidabile) && $aziendaPiuAffidabile) : ?>

                    <h4>
                        <?= htmlspecialchars($aziendaPiuAffidabile['nome']) ?>
                    </h4>

                    <p class="mb-1">
                        Affidabilità:
                    </p>

                    <h2>
                        <?= $aziendaPiuAffidabile['percentuale_affidabilita'] ?>%
                    </h2>

                    <p class="text-muted">
                        Bilanci approvati senza rilievi:
                        <?= $aziendaPiuAffidabile['bilanci_approvati'] ?>
                        /
                        <?= $aziendaPiuAffidabile['bilanci_conclusi'] ?>
                    </p>

                <?php else : ?>

                    <p class="text-muted">
                        Nessun dato disponibile.
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>


<hr class="my-4">


<h3 class="mb-3">
    Classifica bilanci per indicatori ESG
</h3>

<p class="text-muted">
    I bilanci sono ordinati in base al numero totale di indicatori ESG
    collegati alle singole voci contabili.
</p>


<div class="table-responsive">

    <table class="table table-striped table-bordered align-middle">

        <thead>

            <tr>

                <th>Posizione</th>

                <th>ID Bilancio</th>

                <th>Azienda</th>

                <th>Numero indicatori ESG</th>

            </tr>

        </thead>

        <tbody>

        <?php if(!empty($classificaBilanci)) : ?>

            <?php $posizione = 1; ?>

            <?php foreach($classificaBilanci as $bilancio) : ?>

                <tr>

                    <td>
                        <?= $posizione ?>
                    </td>

                    <td>
                        <?= $bilancio['id_bilancio'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($bilancio['azienda']) ?>
                    </td>

                    <td>
                        <?= $bilancio['totale_indicatori_esg'] ?>
                    </td>

                </tr>

                <?php $posizione++; ?>

            <?php endforeach; ?>

        <?php else : ?>

            <tr>

                <td colspan="4" class="text-center text-muted">
                    Nessun bilancio disponibile.
                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

</div>

</body>
</html>