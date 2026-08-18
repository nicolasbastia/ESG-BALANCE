<?php

/** @var array $bilanci */
/** @var array $aziende */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Bilanci Aziendali

</h2>

<form
    method="POST"
    action="/esg-balance/bilanci.php?action=create"
    class="mb-5"
>

    <div class="mb-3">

        <label class="form-label">
            Azienda
        </label>

        <select
            name="id_azienda"
            class="form-select"
            required
        >

            <option value="">
                Seleziona azienda
            </option>

            <?php foreach($aziende as $a) : ?>

                <option
                    value="<?= $a['id_azienda'] ?>"
                >

                    <?= $a['nome'] ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <button class="btn btn-success">

        Crea Bilancio

    </button>

</form>

<hr>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>
            <th>Azienda</th>
            <th>Data</th>
            <th>Stato</th>
            <th>Azioni</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($bilanci as $b) : ?>

        <tr>

            <td>
                <?= $b['id_bilancio'] ?>
            </td>

            <td>
                <?= $b['nome_azienda'] ?>
            </td>

            <td>
                <?= $b['data_creazione'] ?>
            </td>

            <td>

                <span class="badge bg-secondary">

                    <?= $b['stato'] ?>

                </span>

            </td>

        <td>

            <a

                href="/esg-balance/voci_bilancio.php?id=<?= $b['id_bilancio'] ?>"

                class="btn btn-primary btn-sm mb-1"
            >

                Gestisci Voci

            </a>

            <a

                href="/esg-balance/esg_bilancio.php?id=<?= $b['id_bilancio'] ?>"

                class="btn btn-warning btn-sm mb-1"
            >

                ESG

            </a>

            <a

                href="/esg-balance/bilanci.php?action=dettaglio&id=<?= $b['id_bilancio'] ?>"

                class="btn btn-info btn-sm mb-1"
            >

                Dettaglio Revisione

            </a>

            <br>

            <a

                href="/esg-balance/bilanci.php?action=delete&id=<?= $b['id_bilancio'] ?>"

                class="btn btn-danger btn-sm"
            >

                Elimina

            </a>

        </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php
include __DIR__ . '/../partials/footer.php';
?>