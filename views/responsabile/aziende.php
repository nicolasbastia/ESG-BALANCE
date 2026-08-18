<?php

/** @var array $aziende */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Le mie aziende

</h2>

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
        >

    </div>

    <button class="btn btn-success">

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
                <?= $a['id_azienda'] ?>
            </td>

            <td>
                <?= $a['nome'] ?>
            </td>

            <td>
                <?= $a['settore'] ?>
            </td>

            <td>

                <?php if($a['logo']) : ?>

                    <img

                        src="/esg-balance/<?= $a['logo'] ?>"

                        width="80"
                    >

                <?php endif; ?>

            </td>

            <td>

                <a

                    href="/esg-balance/aziende.php?action=delete&id=<?= $a['id_azienda'] ?>"

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