<?php

/** @var array $voci */
/** @var array $indicatori */
/** @var array $collegamenti */
/** @var int|string $idBilancio */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Collegamenti ESG Bilancio

</h2>

<form
    method="POST"
    action="/esg-balance/esg_bilancio.php?action=create"
    class="mb-5"
>

    <input
        type="hidden"
        name="id_bilancio"
        value="<?= $idBilancio ?>"
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

            <?php foreach($voci as $v) : ?>

                <option value="<?= $v['id_voce_bilancio'] ?>">

                    <?= $v['nome'] ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Indicatore ESG
        </label>

        <select
            name="id_indicatore"
            class="form-select"
            required
        >

            <?php foreach($indicatori as $i) : ?>

                <option
                    value="<?= $i['id_indicatore'] ?>"
                >

                    <?= $i['nome'] ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Valore ESG
        </label>

        <input
            type="number"
            step="0.01"
            name="valore"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Fonte
        </label>

        <input
            type="text"
            name="fonte"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Data rilevazione
        </label>

        <input
            type="date"
            name="data_rilevazione"
            class="form-control"
            required
        >

    </div>

    <button class="btn btn-success">

        Collega Indicatore

    </button>

</form>

<hr>

<h3>

    Indicatori Collegati

</h3>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Voce</th>
            <th>Indicatore</th>
            <th>Valore ESG</th>
            <th>Fonte</th>
            <th>Data</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($collegamenti as $c) : ?>

        <tr>

            <td>
                <?= $c['nome_voce'] ?>
            </td>

            <td>
                <?= $c['nome_indicatore'] ?>
            </td>

            <td>
                <?= $c['valore_indicatore'] ?>
            </td>

            <td>
                <?= $c['fonte'] ?>
            </td>

            <td>
                <?= $c['data_rilevazione'] ?>
            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php
$backUrl = 'bilanci.php';
include __DIR__ . '/../partials/back_button.php';
?>