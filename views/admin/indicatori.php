<?php
/** @var array $indicatori */
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Indicatori ESG

</h2>

<form
    method="POST"
    action="indicatori.php?action=create"
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
            Immagine
        </label>

        <input
            type="text"
            name="immagine"
            class="form-control"
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Rilevanza
        </label>

        <input
            type="number"
            min="0"
            max="10"
            name="rilevanza"
            class="form-control"
            required
        >

    </div>

    <button
        class="btn btn-success"
    >

        Aggiungi Indicatore

    </button>

</form>

<hr>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>
            <th>Nome</th>
            <th>Rilevanza</th>
            <th>Azioni</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($indicatori as $i) : ?>

        <tr>

            <td>
                <?= $i['id_indicatore'] ?>
            </td>

            <td>
                <?= $i['nome'] ?>
            </td>

            <td>
                <?= $i['rilevanza'] ?>
            </td>

            <td>

                <a

                    href="indicatori.php?action=delete&id=<?= $i['id_indicatore'] ?>"

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