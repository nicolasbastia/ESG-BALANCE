<?php

/** @var array $template */
/** @var array $valori */
/** @var int $idBilancio */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/bilanci.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">
    Voci Bilancio
</h2>

<?php if(isset($_SESSION['errore_bilancio'])) : ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['errore_bilancio']) ?>
    </div>

    <?php unset($_SESSION['errore_bilancio']); ?>

<?php endif; ?>

<?php if(isset($_SESSION['successo_bilancio'])) : ?>

    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['successo_bilancio']) ?>
    </div>

    <?php unset($_SESSION['successo_bilancio']); ?>

<?php endif; ?>

<form
    method="POST"
    action="/esg-balance/voci_bilancio.php?action=save"
>

    <input
        type="hidden"
        name="id_bilancio"
        value="<?= htmlspecialchars((string) $idBilancio) ?>"
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

            <?php
                $idVoce = (int) $t['id_voce'];

                $valoreCorrente =
                    isset($valori[$idVoce])
                        ? $valori[$idVoce]
                        : '';
            ?>

            <tr>

                <td>

                    <strong>
                        <?= htmlspecialchars($t['nome']) ?>
                    </strong>

                    <br>

                    <small class="text-muted">
                        <?= htmlspecialchars($t['descrizione'] ?? '') ?>
                    </small>

                </td>

                <td>

                    <input
                        type="number"
                        step="0.01"
                        name="valori[<?= htmlspecialchars((string) $idVoce) ?>]"
                        value="<?= htmlspecialchars((string) $valoreCorrente) ?>"
                        class="form-control"
                        required
                    >

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

    <button
        type="submit"
        class="btn btn-success"
    >
        Salva Bilancio
    </button>

</form>

<hr>

<h3 class="mt-4">
    Riepilogo Bilancio
</h3>

<?php if(empty($valori)) : ?>

    <div class="alert alert-info">
        Non sono ancora presenti valori salvati.
    </div>

<?php else : ?>

    <table class="table table-striped">

        <thead>

            <tr>
                <th>Voce</th>
                <th>Valore Salvato</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($template as $t) : ?>

            <?php
                $idVoce = (int) $t['id_voce'];
            ?>

            <?php if(isset($valori[$idVoce])) : ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($t['nome']) ?>
                    </td>

                    <td>
                        €
                        <?= number_format(
                            (float) $valori[$idVoce],
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

<?php endif; ?>