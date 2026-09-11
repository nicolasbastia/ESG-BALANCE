<?php

/** @var array $dettagli */
/** @var NotaRevisione[] $note */
/** @var GiudizioRevisione|null $giudizio */
/** @var int $idBilancio */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/revisioni_revisore.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Dettaglio Revisione ESG

</h2>


<!-- ========================================================= -->
<!-- MESSAGGI -->
<!-- ========================================================= -->

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


<!-- ========================================================= -->
<!-- DATI BILANCIO -->
<!-- ========================================================= -->

<?php if(empty($dettagli)) : ?>

    <div class="alert alert-info">

        Questo bilancio non contiene ancora voci da revisionare.

    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>Voce Bilancio</th>
                <th>Valore Economico</th>
                <th>Indicatore ESG</th>
                <th>Valore ESG</th>
                <th>Fonte</th>
                <th>Data</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($dettagli as $d) : ?>

            <tr>

                <td>

                    <?= htmlspecialchars($d['voce']) ?>

                </td>

                <td>

                    € <?= number_format(
                        (float) $d['valore'],
                        2,
                        ',',
                        '.'
                    ) ?>

                </td>

                <td>

                    <?= htmlspecialchars($d['indicatore'] ?? '-') ?>

                </td>

                <td>

                    <?= htmlspecialchars(
                        isset($d['valore_indicatore'])
                            ? (string) $d['valore_indicatore']
                            : '-'
                    ) ?>

                </td>

                <td>

                    <?= htmlspecialchars($d['fonte'] ?? '-') ?>

                </td>

                <td>

                    <?= htmlspecialchars($d['data_rilevazione'] ?? '-') ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>


<hr>


<!-- ========================================================= -->
<!-- CREA NOTA -->
<!-- ========================================================= -->

<?php if(!$giudizio) : ?>

    <h3 class="mt-4">

        Inserisci Nota Revisione

    </h3>

    <?php if(empty($dettagli)) : ?>

        <div class="alert alert-warning">

            Non puoi inserire una nota perché il bilancio non contiene voci.

        </div>

    <?php else : ?>

        <form
            method="POST"
            action="/esg-balance/revisioni_revisore.php?action=nota"
        >

            <input
                type="hidden"
                name="id_bilancio"
                value="<?= htmlspecialchars((string) $idBilancio) ?>"
            >

            <div class="mb-3">

                <label class="form-label">

                    Voce Bilancio

                </label>

                <select
                    name="id_voce_bilancio"
                    class="form-select"
                    required
                >

                    <option value="">

                        Seleziona una voce

                    </option>

                    <?php foreach($dettagli as $d) : ?>

                        <option
                            value="<?= htmlspecialchars(
                                (string) $d['id_voce_bilancio']
                            ) ?>"
                        >

                            <?= htmlspecialchars($d['voce']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="mb-3">

                <label class="form-label">

                    Nota

                </label>

                <textarea
                    name="testo"
                    class="form-control"
                    rows="4"
                    required
                ></textarea>

            </div>


            <button
                type="submit"
                class="btn btn-warning"
            >

                Salva Nota

            </button>

        </form>

    <?php endif; ?>

<?php else : ?>

    <div class="alert alert-info">

        La revisione è conclusa.
        Non è più possibile inserire nuove note.

    </div>

<?php endif; ?>


<hr>


<!-- ========================================================= -->
<!-- NOTE INSERITE -->
<!-- ========================================================= -->

<h3 class="mt-4">

    Note Inserite

</h3>

<?php if(empty($note)) : ?>

    <p class="text-muted">

        Nessuna nota inserita.

    </p>

<?php else : ?>

    <table class="table table-striped">

        <thead>

            <tr>

                <th>Voce</th>
                <th>Nota</th>
                <th>Data</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($note as $n) : ?>

            <tr>

                <td>

                    <?= htmlspecialchars($n->voce ?? '') ?>

                </td>

                <td>

                    <?= nl2br(
                        htmlspecialchars($n->testo)
                    ) ?>

                </td>

                <td>

                    <?= htmlspecialchars($n->data_nota) ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>


<hr>


<!-- ========================================================= -->
<!-- GIUDIZIO FINALE -->
<!-- ========================================================= -->

<?php if(!$giudizio) : ?>

    <h3 class="mt-4">

        Giudizio Finale Revisione

    </h3>

    <form
        method="POST"
        action="/esg-balance/revisioni_revisore.php?action=giudizio"
    >

        <input
            type="hidden"
            name="id_bilancio"
            value="<?= htmlspecialchars((string) $idBilancio) ?>"
        >

        <div class="mb-3">

            <label class="form-label">

                Esito

            </label>

            <select
                name="esito"
                class="form-select"
                required
            >

                <option value="approvazione">

                    Approvato

                </option>

                <option value="approvazione con rilievi">

                    Approvato con rilievi

                </option>

                <option value="respingimento">

                    Respinto

                </option>

            </select>

        </div>


        <div class="mb-3">

            <label class="form-label">

                Rilievi

            </label>

            <textarea
                name="rilievi"
                class="form-control"
                rows="4"
            ></textarea>

        </div>


        <button
            type="submit"
            class="btn btn-success"
        >

            Salva Giudizio

        </button>

    </form>

<?php endif; ?>


<!-- ========================================================= -->
<!-- GIUDIZIO SALVATO -->
<!-- ========================================================= -->

<?php if($giudizio) : ?>

    <hr>

    <h3 class="mt-4">

        Giudizio Salvato

    </h3>

    <div class="card border-success">

        <div class="card-body">

            <h5>

                Esito:

                <span class="badge bg-success">

                    <?= htmlspecialchars($giudizio->esito) ?>

                </span>

            </h5>

            <p class="mt-3">

                <strong>

                    Rilievi:

                </strong>

                <br>

                <?php if(!empty($giudizio->rilievi)) : ?>

                    <?= nl2br(
                        htmlspecialchars($giudizio->rilievi)
                    ) ?>

                <?php else : ?>

                    <span class="text-muted">
                        Nessun rilievo.
                    </span>

                <?php endif; ?>

            </p>

            <p class="text-muted">

                Data:

                <?= htmlspecialchars($giudizio->data_giudizio) ?>

            </p>

        </div>

    </div>

<?php endif; ?>


<?php

include __DIR__ . '/../partials/footer.php';

?>