<?php

include __DIR__ . '/../partials/header.php';

?>

<?php
$backUrl = '/esg-balance/index.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">
    Competenze e CV Utenti
</h2>

<!-- REVISORI ESG              -->

<h3 class="mb-3">
    Revisori ESG
</h3>

<?php if(empty($revisori)) : ?>

    <div class="alert alert-info">
        Nessun revisore ESG presente.
    </div>

<?php else : ?>

    <div class="table-responsive mb-5">

        <table class="table table-striped table-bordered align-middle">

            <thead>

                <tr>
                    <th>Username</th>
                    <th>Affidabilità</th>
                    <th>Competenze</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach($revisori as $revisore) : ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($revisore['username']) ?>
                        </td>

                        <td>
                            <?php if($revisore['indice_affidabilita'] !== null) : ?>

                                <?= htmlspecialchars($revisore['indice_affidabilita']) ?>%

                            <?php else : ?>

                                Non disponibile

                            <?php endif; ?>
                        </td>

                        <td>

                            <?php if(empty($revisore['competenze'])) : ?>

                                <span class="text-muted">
                                    Nessuna competenza inserita
                                </span>

                            <?php else : ?>

                                <ul class="mb-0">

                                    <?php foreach($revisore['competenze'] as $competenza) : ?>

                                        <li>
                                            <?= htmlspecialchars($competenza['nome']) ?>
                                            -
                                            Livello
                                            <?= (int)$competenza['livello'] ?>/5
                                        </li>

                                    <?php endforeach; ?>

                                </ul>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php endif; ?>


<!-- RESPONSABILI AZIENDALI    -->

<h3 class="mb-3">
    Responsabili Aziendali
</h3>

<?php if(empty($responsabili)) : ?>

    <div class="alert alert-info">
        Nessun responsabile aziendale presente.
    </div>

<?php else : ?>

    <div class="table-responsive">

        <table class="table table-striped table-bordered align-middle">

            <thead>

                <tr>
                    <th>Username</th>
                    <th>Aziende</th>
                    <th>CV</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach($responsabili as $responsabile) : ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($responsabile['username']) ?>
                        </td>

                        <td>

                            <?php if(empty($responsabile['aziende'])) : ?>

                                <span class="text-muted">
                                    Nessuna azienda associata
                                </span>

                            <?php else : ?>

                                <ul class="mb-0">

                                    <?php foreach($responsabile['aziende'] as $azienda) : ?>

                                        <li>
                                            <?= htmlspecialchars($azienda['nome']) ?>
                                        </li>

                                    <?php endforeach; ?>

                                </ul>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if(!empty($responsabile['cv_pdf'])) : ?>

                                <a
                                    href="/esg-balance/<?= htmlspecialchars($responsabile['cv_pdf']) ?>"                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-primary"
                                >
                                    Visualizza CV
                                </a>

                            <?php else : ?>

                                <span class="text-muted">
                                    CV non caricato
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php endif; ?>

<?php

include __DIR__ . '/../partials/footer.php';

?>