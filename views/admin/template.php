<?php

/** @var VoceTemplate[] $voci */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/index.php';
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

<?php if(empty($voci)) : ?>

    <div class="alert alert-info">
        Non sono ancora presenti voci nel template.
    </div>

<?php else : ?>

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
                    <?= htmlspecialchars((string) $v->id_voce) ?>
                </td>

                <td>
                    <?= htmlspecialchars($v->nome) ?>
                </td>

                <td>
                    <?= htmlspecialchars($v->descrizione ?? '') ?>
                </td>

                <td>

                    <form
                        method="POST"
                        action="/esg-balance/template.php?action=delete"
                        class="d-inline"
                        onsubmit="return confirm('Vuoi eliminare questa voce del template?');"
                    >

                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars((string) $v->id_voce) ?>"
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