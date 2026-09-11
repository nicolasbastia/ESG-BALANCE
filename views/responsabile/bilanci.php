<?php

/** @var Bilancio[] $bilanci */
/** @var array $aziende */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">
    Bilanci Aziendali
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
                    value="<?= htmlspecialchars((string) $a['id_azienda']) ?>"
                >
                    <?= htmlspecialchars($a['nome']) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Crea Bilancio
    </button>

</form>

<hr>

<?php if(empty($bilanci)) : ?>

    <div class="alert alert-info">
        Non sono presenti bilanci.
    </div>

<?php else : ?>

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
                    <?= htmlspecialchars((string) $b->id_bilancio) ?>
                </td>

                <td>
                    <?= htmlspecialchars($b->nome_azienda ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($b->data_creazione) ?>
                </td>

                <td>

                    <span class="badge bg-secondary">
                        <?= htmlspecialchars($b->stato) ?>
                    </span>

                </td>

                <td>

                    <a
                        href="/esg-balance/voci_bilancio.php?id=<?= htmlspecialchars((string) $b->id_bilancio) ?>"
                        class="btn btn-primary btn-sm mb-1"
                    >
                        Gestisci Voci
                    </a>

                    <a
                        href="/esg-balance/esg_bilancio.php?id=<?= htmlspecialchars((string) $b->id_bilancio) ?>"
                        class="btn btn-warning btn-sm mb-1"
                    >
                        ESG
                    </a>

                    <a
                        href="/esg-balance/bilanci.php?action=dettaglio&id=<?= htmlspecialchars((string) $b->id_bilancio) ?>"
                        class="btn btn-info btn-sm mb-1"
                    >
                        Dettaglio Revisione
                    </a>

                    <br>

                    <form
                        method="POST"
                        action="/esg-balance/bilanci.php?action=delete"
                        class="d-inline"
                        onsubmit="return confirm('Vuoi davvero eliminare questo bilancio?');"
                    >

                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars((string) $b->id_bilancio) ?>"
                        >

                        <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                        >
                            Elimina
                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>