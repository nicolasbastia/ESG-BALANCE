<?php

/** @var array $aziende */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">
    Le mie aziende
</h2>

<?php if(isset($_SESSION['errore_azienda'])) : ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['errore_azienda']) ?>
    </div>

    <?php unset($_SESSION['errore_azienda']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['successo_azienda'])) : ?>

    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['successo_azienda']) ?>
    </div>

    <?php unset($_SESSION['successo_azienda']); ?>

<?php endif; ?>


<form
    method="POST"
    action="/esg-balance/aziende.php?action=create"
    enctype="multipart/form-data"
    class="mb-5"
>

    <div class="mb-3">

        <label class="form-label">
            Nome
        </label>

        <input
            type="text"
            name="nome"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Ragione Sociale
        </label>

        <input
            type="text"
            name="ragione_sociale"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Partita IVA
        </label>

        <input
            type="text"
            name="partita_iva"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Settore
        </label>

        <input
            type="text"
            name="settore"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Numero Dipendenti
        </label>

        <input
            type="number"
            name="numero_dipendenti"
            class="form-control"
            min="0"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Logo
        </label>

        <input
            type="file"
            name="logo"
            class="form-control"
            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
        >

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Crea Azienda
    </button>

</form>

<hr>

<?php if(empty($aziende)) : ?>

    <div class="alert alert-info">
        Non hai ancora registrato nessuna azienda.
    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Settore</th>
                <th>Logo</th>
                <th>Azioni</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($aziende as $a) : ?>

            <tr>

                <td>
                    <?= htmlspecialchars((string) $a->id_azienda) ?>
                </td>

                <td>
                    <?= htmlspecialchars($a->nome) ?>
                </td>

                <td>
                    <?= htmlspecialchars($a->settore ?? '') ?>
                </td>

                <td>

                    <?php if(!empty($a->logo)) : ?>

                        <img
                            src="/esg-balance/<?= htmlspecialchars($a->logo) ?>"
                            width="80"
                            alt="Logo <?= htmlspecialchars($a->nome) ?>"
                        >

                    <?php endif; ?>

                </td>

                <td>

                    <form
                        method="POST"
                        action="/esg-balance/aziende.php?action=delete"
                        class="d-inline"
                        onsubmit="return confirm('Vuoi davvero eliminare questa azienda?');"
                    >

                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars((string) $a->id_azienda) ?>"
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

<?php

include __DIR__ . '/../partials/footer.php';

?>