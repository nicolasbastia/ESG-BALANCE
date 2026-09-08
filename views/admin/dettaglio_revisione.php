<?php

/** @var array $dettagli */
/** @var array $note */
/** @var array $giudizi */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/revisioni.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">
    Dettaglio Revisione ESG
</h2>


<!-- DATI DEL BILANCIO -->

<h4 class="mb-3">
    Dati del Bilancio
</h4>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Voce Bilancio</th>
            <th>Valore Economico</th>
            <th>Indicatore ESG</th>
            <th>Valore ESG</th>
            <th>Fonte</th>
            <th>Data Rilevazione</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($dettagli as $d) : ?>

        <tr>

            <td>
                <?= htmlspecialchars($d['voce']) ?>
            </td>

            <td>
                <?= htmlspecialchars($d['valore']) ?>
            </td>

            <td>
                <?= htmlspecialchars($d['indicatore'] ?? '-') ?>
            </td>

            <td>
                <?= htmlspecialchars($d['valore_indicatore'] ?? '-') ?>
            </td>

            <td>
                <?= htmlspecialchars($d['fonte'] ?? '-') ?>
            </td>

            <td>
                <?= htmlspecialchars($d['data_rilevazione'] ?? '-') ?>
            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>


<hr>


<!-- NOTE DEI REVISORI -->

<h4 class="mb-3">
    Note dei Revisori
</h4>

<?php if(empty($note)) : ?>

    <div class="alert alert-secondary">

        Nessuna nota inserita.

    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>Revisore</th>
                <th>Voce</th>
                <th>Nota</th>
                <th>Data</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($note as $n) : ?>

            <tr>

                <td>
                    <?= htmlspecialchars($n['revisore']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($n['voce']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($n['testo']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($n['data_nota']) ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>


<hr>


<!-- GIUDIZI DEI REVISORI -->

<h4 class="mb-3">
    Giudizi dei Revisori
</h4>

<?php if(empty($giudizi)) : ?>

    <div class="alert alert-secondary">

        Nessun giudizio inserito.

    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>Revisore</th>
                <th>Esito</th>
                <th>Rilievi</th>
                <th>Data</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($giudizi as $g) : ?>

            <tr>

                <td>
                    <?= htmlspecialchars($g['revisore']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($g['esito']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($g['rilievi'] ?? '-') ?>
                </td>

                <td>
                    <?= htmlspecialchars($g['data_giudizio']) ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>


<?php

include __DIR__ . '/../partials/footer.php';

?>