<?php

/** @var array $bilanci */
/** @var array $revisori */
/** @var array $revisioni */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/index.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Assegna Revisori ESG

</h2>


<?php if(isset($_SESSION['errore_revisione'])) : ?>

    <div class="alert alert-danger">

        <?= htmlspecialchars($_SESSION['errore_revisione']) ?>

    </div>

    <?php unset($_SESSION['errore_revisione']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['successo_revisione'])) : ?>

    <div class="alert alert-success">

        <?= htmlspecialchars($_SESSION['successo_revisione']) ?>

    </div>

    <?php unset($_SESSION['successo_revisione']); ?>

<?php endif; ?>


<form
    method="POST"
    action="/esg-balance/revisioni.php?action=assegna"
    class="mb-5"
>

    <div class="mb-3">

        <label class="form-label">
            Bilancio
        </label>

        <select
            name="id_bilancio"
            class="form-select"
            required
        >

            <?php foreach($bilanci as $b) : ?>

                <option
                    value="<?= $b['id_bilancio'] ?>"
                >

                    <?= $b['azienda'] ?>
                    -
                    <?= $b['data_creazione'] ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Revisore ESG
        </label>

        <select
            name="id_revisore"
            class="form-select"
            required
        >

            <?php foreach($revisori as $r) : ?>

                <option
                    value="<?= $r['id_utente'] ?>"
                >

                    <?= $r['username'] ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <button class="btn btn-success">

        Assegna Revisore

    </button>

</form>

<hr>

<h3>

    Revisioni Assegnate

</h3>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>
            <th>Azienda</th>
            <th>Revisore</th>
            <th>Azioni</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($revisioni as $r) : ?>

        <tr>

            <td>
                <?= $r->id_revisione ?>
            </td>

            <td>
                <?= $r->azienda ?>
            </td>

            <td>
                <?= $r->username ?>
            </td>

            <td>

                <a

                    href="/esg-balance/revisioni.php?action=dettaglioAdmin&id=<?= $r->id_bilancio ?>"

                    class="btn btn-info btn-sm"
                >

                    Dettaglio Revisione

                </a>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>
