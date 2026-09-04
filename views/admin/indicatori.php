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

    <!-- CAMPI AMBIENTALI -->

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

    <!-- CAMPI SOCIALI -->

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

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>
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
                <?= $i['id_indicatore'] ?>
            </td>

            <td>
                <?= htmlspecialchars($i['nome']) ?>
            </td>

            <td>
                <?= $i['rilevanza'] ?> / 10
            </td>

            <td>

                <?php if($i['categoria'] === 'ambientale') : ?>

                    <span class="badge bg-success">
                        Ambientale
                    </span>

                <?php elseif($i['categoria'] === 'sociale') : ?>

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

                <?php if($i['categoria'] === 'ambientale') : ?>

                    <strong>
                        Codice normativa:
                    </strong>

                    <?= htmlspecialchars(
                        $i['codice_normativa'] ?? ''
                    ) ?>

                <?php elseif($i['categoria'] === 'sociale') : ?>

                    <strong>
                        Ambito:
                    </strong>

                    <?= htmlspecialchars(
                        $i['ambito_sociale'] ?? ''
                    ) ?>

                    <br>

                    <strong>
                        Frequenza:
                    </strong>

                    <?= htmlspecialchars(
                        $i['frequenza_rilevazione'] ?? ''
                    ) ?>

                <?php else : ?>

                    -

                <?php endif; ?>

            </td>

            <td>

                <a
                    href="indicatori.php?action=delete&id=<?= $i['id_indicatore'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Vuoi eliminare questo indicatore ESG?');"
                >

                    Elimina

                </a>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<script>

const categoria = document.getElementById('categoria');

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

    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    campiAmbientali.style.display = 'none';

    campiSociali.style.display = 'none';

    codiceNormativa.required = false;

    ambitoSociale.required = false;

    frequenzaRilevazione.required = false;


    /*
    |--------------------------------------------------------------------------
    | AMBIENTALE
    |--------------------------------------------------------------------------
    */

    if(valore === 'ambientale') {

        campiAmbientali.style.display = 'block';

        codiceNormativa.required = true;
    }


    /*
    |--------------------------------------------------------------------------
    | SOCIALE
    |--------------------------------------------------------------------------
    */

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