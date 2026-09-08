<?php

/** @var array $template */
/** @var array $valori */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/bilanci.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Voci Bilancio

</h2>

<form
    method="POST"
    action="/esg-balance/voci_bilancio.php?action=save"
>

    <input
        type="hidden"
        name="id_bilancio"
        value="<?= $_GET['id'] ?>"
    >

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>Voce</th>
                <th>Valore</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($template as $t) : ?>

            <tr>

                <td>

                    <strong>
                        <?= $t['nome'] ?>
                    </strong>

                    <br>

                    <small class="text-muted">

                        <?= $t['descrizione'] ?>

                    </small>

                </td>

                <td>

                    <input
                        type="number"
                        step="0.01"
                        name="valori[<?= $t['id_voce'] ?>]"
                        value="<?= isset($valori[(int)$t['id_voce']]) ? $valori[(int)$t['id_voce']] : '' ?>"
                        class="form-control"
                    >

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

    <button class="btn btn-success">

        Salva Bilancio

    </button>

</form>

<hr>

<h3 class="mt-4">

    Riepilogo Bilancio

</h3>

<table class="table table-striped">

    <thead>

        <tr>

            <th>Voce</th>
            <th>Valore Salvato</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($template as $t) : ?>

        <?php if(isset($valori[(int)$t['id_voce']])) : ?>

            <tr>

                <td>
                    <?= $t['nome'] ?>
                </td>

                <td>

                    €
                    <?= number_format(

                        $valori[(int)$t['id_voce']],
                        2,
                        ',',
                        '.'

                    ) ?>

                </td>

            </tr>

        <?php endif; ?>

    <?php endforeach; ?>

    </tbody>

</table>

