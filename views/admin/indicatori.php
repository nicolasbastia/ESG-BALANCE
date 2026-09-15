<?php

/** @var IndicatoreESG[] $indicatori */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">
    Indicatori ESG
</h2>

<?php if(isset($_SESSION['errore_indicatore'])) : ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['errore_indicatore']) ?>
    </div>

    <?php unset($_SESSION['errore_indicatore']); ?>

<?php endif; ?>

<?php if(isset($_SESSION['successo_indicatore'])) : ?>

    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['successo_indicatore']) ?>
    </div>

    <?php unset($_SESSION['successo_indicatore']); ?>

<?php endif; ?>

<form
    method="POST"
    action="/esg-balance/indicatori.php?action=create"
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
            Immagine
        </label>

        <input
            type="file"
            name="immagine"
            class="form-control"
            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
        >

        <div class="form-text">
            Formati consentiti: JPG, PNG e WEBP.
        </div>

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

    <div class="mb-3">

        <label class="form-label">
            Categoria
        </label>

        <select
            name="categoria"
            id="categoria"
            class="form-select"
            required
        >

            <option value="nessuna">
                Nessuna
            </option>

            <option value="ambientale">
                Ambientale
            </option>

            <option value="sociale">
                Sociale
            </option>

        </select>

    </div>

    <div
        id="campiAmbientali"
        style="display: none;"
    >

        <div class="mb-3">

            <label class="form-label">
                Codice normativa di rilevamento
            </label>

            <input
                type="text"
                name="codice_normativa"
                id="codiceNormativa"
                class="form-control"
            >

        </div>

    </div>

    <div
        id="campiSociali"
        style="display: none;"
    >

        <div class="mb-3">

            <label class="form-label">
                Ambito sociale di riferimento
            </label>

            <input
                type="text"
                name="ambito_sociale"
                id="ambitoSociale"
                class="form-control"
            >

        </div>

        <div class="mb-3">

            <label class="form-label">
                Frequenza di rilevazione
            </label>

            <input
                type="text"
                name="frequenza_rilevazione"
                id="frequenzaRilevazione"
                class="form-control"
                placeholder="Es. Annuale, Mensile, Trimestrale"
            >

        </div>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Aggiungi Indicatore
    </button>

</form>

<hr>

<h3 class="mb-3">
    Indicatori presenti
</h3>

<?php if(empty($indicatori)) : ?>

    <div class="alert alert-info">
        Non sono ancora presenti indicatori ESG.
    </div>

<?php else : ?>

    <table class="table table-bordered align-middle">

        <thead>

            <tr>
                <th>ID</th>
                <th>Immagine</th>
                <th>Nome</th>
                <th>Rilevanza</th>
                <th>Categoria</th>
                <th>Dettagli specifici</th>
                <th>Azioni</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($indicatori as $i) : ?>

            <tr>

                <td>
                    <?= htmlspecialchars((string) $i->id_indicatore) ?>
                </td>

                <td>

                    <?php if(!empty($i->immagine)) : ?>

                        <img
                            src="/esg-balance/<?= htmlspecialchars($i->immagine) ?>"
                            alt="<?= htmlspecialchars($i->nome) ?>"
                            style="
                                width: 70px;
                                height: 70px;
                                object-fit: cover;
                                border-radius: 5px;
                            "
                        >

                    <?php else : ?>

                        <span class="text-muted">
                            Nessuna
                        </span>

                    <?php endif; ?>

                </td>

                <td>
                    <?= htmlspecialchars($i->nome) ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $i->rilevanza) ?> / 10
                </td>

                <td>

                    <?php if($i->categoria === 'ambientale') : ?>

                        <span class="badge bg-success">
                            Ambientale
                        </span>

                    <?php elseif($i->categoria === 'sociale') : ?>

                        <span class="badge bg-primary">
                            Sociale
                        </span>

                    <?php else : ?>

                        <span class="badge bg-secondary">
                            Nessuna
                        </span>

                    <?php endif; ?>

                </td>

                <td>

                    <?php if($i->categoria === 'ambientale') : ?>

                        <strong>
                            Codice normativa:
                        </strong>

                        <?= htmlspecialchars(
                            $i->codice_normativa ?? ''
                        ) ?>

                    <?php elseif($i->categoria === 'sociale') : ?>

                        <strong>
                            Ambito:
                        </strong>

                        <?= htmlspecialchars(
                            $i->ambito_sociale ?? ''
                        ) ?>

                        <br>

                        <strong>
                            Frequenza:
                        </strong>

                        <?= htmlspecialchars(
                            $i->frequenza_rilevazione ?? ''
                        ) ?>

                    <?php else : ?>

                        -

                    <?php endif; ?>

                </td>

                <td>

                    <form
                        method="POST"
                        action="/esg-balance/indicatori.php?action=delete"
                        class="d-inline"
                        onsubmit="return confirm('Vuoi eliminare questo indicatore ESG?');"
                    >

                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars((string) $i->id_indicatore) ?>"
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

<script>

const categoria =
    document.getElementById('categoria');

const campiAmbientali =
    document.getElementById('campiAmbientali');

const campiSociali =
    document.getElementById('campiSociali');

const codiceNormativa =
    document.getElementById('codiceNormativa');

const ambitoSociale =
    document.getElementById('ambitoSociale');

const frequenzaRilevazione =
    document.getElementById('frequenzaRilevazione');


function aggiornaCampiCategoria() {

    const valore = categoria.value;

    campiAmbientali.style.display = 'none';

    campiSociali.style.display = 'none';

    codiceNormativa.required = false;

    ambitoSociale.required = false;

    frequenzaRilevazione.required = false;


    if(valore === 'ambientale') {

        campiAmbientali.style.display = 'block';

        codiceNormativa.required = true;
    }


    if(valore === 'sociale') {

        campiSociali.style.display = 'block';

        ambitoSociale.required = true;

        frequenzaRilevazione.required = true;
    }
}


categoria.addEventListener(
    'change',
    aggiornaCampiCategoria
);

aggiornaCampiCategoria();

</script>

<?php
include __DIR__ . '/../partials/footer.php';
?>