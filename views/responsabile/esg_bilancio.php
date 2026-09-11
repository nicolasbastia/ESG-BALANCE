<?php

/** @var array $voci */
/** @var array $indicatori */
/** @var VoceIndicatore[] $collegamenti */
/** @var int|string $idBilancio */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/bilanci.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">
    Collegamenti ESG Bilancio
</h2>

<?php if(isset($_SESSION['errore_esg'])) : ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['errore_esg']) ?>
    </div>

    <?php unset($_SESSION['errore_esg']); ?>

<?php endif; ?>

<?php if(isset($_SESSION['successo_esg'])) : ?>

    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['successo_esg']) ?>
    </div>

    <?php unset($_SESSION['successo_esg']); ?>

<?php endif; ?>


<?php if(empty($voci)) : ?>

    <div class="alert alert-warning">
        Prima di collegare un indicatore ESG devi inserire almeno una voce nel bilancio.
    </div>

<?php else : ?>

    <form
        method="POST"
        action="/esg-balance/esg_bilancio.php?action=create"
        class="mb-5"
    >

        <input
            type="hidden"
            name="id_bilancio"
            value="<?= htmlspecialchars((string) $idBilancio) ?>"
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

                <option value="">
                    Seleziona voce
                </option>

                <?php foreach($voci as $v) : ?>

                    <option
                        value="<?= htmlspecialchars((string) $v['id_voce_bilancio']) ?>"
                    >
                        <?= htmlspecialchars($v['nome']) ?>
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

                <option value="">
                    Seleziona indicatore
                </option>

                <?php foreach($indicatori as $i) : ?>

                    <option
                        value="<?= htmlspecialchars((string) $i['id_indicatore']) ?>"
                    >
                        <?= htmlspecialchars($i['nome']) ?>
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

        <button
            type="submit"
            class="btn btn-success"
        >
            Collega Indicatore
        </button>

    </form>

<?php endif; ?>

<hr>

<h3>
    Indicatori Collegati
</h3>

<?php if(empty($collegamenti)) : ?>

    <div class="alert alert-info">
        Non sono ancora presenti indicatori ESG collegati.
    </div>

<?php else : ?>

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
                    <?= htmlspecialchars($c->nome_voce ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($c->nome_indicatore ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $c->valore_indicatore) ?>
                </td>

                <td>
                    <?= htmlspecialchars($c->fonte ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($c->data_rilevazione ?? '') ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>