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
            accept="image/*"
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

                <?= $a->id_azienda ?>

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

                <a
                    href="/esg-balance/aziende.php?action=delete&id=<?= $a->id_azienda ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Vuoi davvero eliminare questa azienda?');"
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