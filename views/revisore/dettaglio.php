<?php

/** @var array $dettagli */
/** @var array $note */
/** @var array|null $giudizio */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Dettaglio Revisione ESG

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

    Inserisci Nota Revisione

</h3>

<form
    method="POST"
    action="/esg-balance/revisioni_revisore.php?action=nota"
>

    <input
        type="hidden"
        name="id_bilancio"
        value="<?= $_GET['id'] ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            Voce Bilancio
        </label>

        <select
            name="id_voce_bilancio"
            class="form-select"
            required
        >

            <?php foreach($dettagli as $d) : ?>

                <option
                    value="<?= $d['id_voce_bilancio'] ?>"
                >

                    <?= $d['voce'] ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Nota
        </label>

        <textarea
            name="testo"
            class="form-control"
            rows="4"
            required
        ></textarea>

    </div>

    <button class="btn btn-warning">

        Salva Nota

    </button>

</form>

<hr>

<h3 class="mt-4">

    Note Inserite

</h3>

<table class="table table-striped">

    <thead>

        <tr>

            <th>Voce</th>
            <th>Nota</th>
            <th>Data</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($note as $n) : ?>

        <tr>

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

<hr>

<h3 class="mt-4">

    Giudizio Finale Revisione

</h3>

<form
    method="POST"
    action="/esg-balance/revisioni_revisore.php?action=giudizio"
>

    <input
        type="hidden"
        name="id_bilancio"
        value="<?= $_GET['id'] ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            Esito
        </label>

        <select
            name="esito"
            class="form-select"
            required
        >

            <option value="approvazione">
                    Approvato
                </option>

                <option value="approvazione con rilievi">
                    Approvato con rilievi
                </option>

                <option value="respingimento">
                    Respinto
                </option>

        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Rilievi
        </label>

        <textarea
            name="rilievi"
            class="form-control"
            rows="4"
        ></textarea>

    </div>

    <button class="btn btn-success">

        Salva Giudizio

    </button>

</form>

<?php if($giudizio) : ?>

<hr>

<h3 class="mt-4">

    Giudizio Salvato

</h3>

<div class="card border-success">

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

<?php endif; ?>

<?php
$backUrl = 'revisioni_revisore.php';
include __DIR__ . '/../partials/back_button.php';
?>