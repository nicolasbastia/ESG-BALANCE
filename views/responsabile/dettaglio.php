<?php

/** @var array $dettagli */
/** @var array $note */
/** @var array|null $giudizio */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Dettaglio Bilancio ESG

</h2>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Voce Bilancio</th>
            <th>Valore Economico</th>
            <th>Indicatore ESG</th>
            <th>Valore ESG</th>
            <th>Fonte</th>
            <th>Data</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($dettagli as $d) : ?>

        <tr>

            <td>

                <?= $d['voce'] ?>

            </td>

            <td>

                €
                <?= number_format(

                    $d['valore'],
                    2,
                    ',',
                    '.'

                ) ?>

            </td>

            <td>

                <?= $d['indicatore'] ?? '-' ?>

            </td>

            <td>

                <?= $d['valore_indicatore'] ?? '-' ?>

            </td>

            <td>

                <?= $d['fonte'] ?? '-' ?>

            </td>

            <td>

                <?= $d['data_rilevazione'] ?? '-' ?>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<hr>

<h3 class="mt-4">

    Stato Revisione ESG

</h3>

<?php if($giudizio) : ?>

<div class="card border-success mb-4">

    <div class="card-body">

        <h5>

            Esito:

            <span class="badge bg-success">

                <?= $giudizio['esito'] ?>

            </span>

        </h5>

        <p class="mt-3">

            <strong>
                Rilievi:
            </strong>

            <br>

            <?= $giudizio['rilievi'] ?>

        </p>

        <p class="text-muted">

            Data:
            <?= $giudizio['data_giudizio'] ?>

        </p>

    </div>

</div>

<?php else : ?>

<div class="alert alert-warning">

    Nessun giudizio disponibile.

</div>

<?php endif; ?>

<hr>

<h3>

    Note Revisore ESG

</h3>

<?php if(count($note) > 0) : ?>

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

                <?= $n['revisore'] ?>

            </td>

            <td>

                <?= $n['voce'] ?>

            </td>

            <td>

                <?= $n['testo'] ?>

            </td>

            <td>

                <?= $n['data_nota'] ?>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php else : ?>

<div class="alert alert-secondary">

    Nessuna nota disponibile.

</div>

<?php endif; ?>

<?php
include __DIR__ . '/../partials/footer.php';
?>