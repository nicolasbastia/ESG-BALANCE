<?php

/** @var array $voci */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Template Bilancio

</h2>

<?php if(isset($_SESSION['errore_template'])) : ?>

    <div class="alert alert-danger">

        <?= htmlspecialchars($_SESSION['errore_template']) ?>

    </div>

    <?php unset($_SESSION['errore_template']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['successo_template'])) : ?>

    <div class="alert alert-success">

        <?= htmlspecialchars($_SESSION['successo_template']) ?>

    </div>

    <?php unset($_SESSION['successo_template']); ?>

<?php endif; ?>


<form
    method="POST"
    action="/esg-balance/template.php?action=create"
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

            Descrizione

        </label>

        <textarea
            name="descrizione"
            class="form-control"
            rows="4"
        ></textarea>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >

        Aggiungi Voce

    </button>

</form>

<hr>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>
            <th>Nome</th>
            <th>Descrizione</th>
            <th>Azioni</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($voci as $v) : ?>

        <tr>

            <td>

                <?= $v->id_voce ?>

            </td>

            <td>

                <?= htmlspecialchars($v->nome) ?>

            </td>

            <td>

                <?= htmlspecialchars($v->descrizione ?? '') ?>

            </td>

            <td>

                <a
                    href="/esg-balance/template.php?action=delete&id=<?= $v->id_voce ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Vuoi eliminare questa voce del template?');"
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