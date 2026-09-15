<?php

/** @var array $bilanci */
/** @var array $revisori */
/** @var Revisione[] $revisioni */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Assegna Revisori ESG

</h2>


<!-- MESSAGGI -->

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


<!-- ASSEGNA REVISIONE -->

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

            <option value="">

                Seleziona bilancio

            </option>

            <?php foreach($bilanci as $b) : ?>

                <option
                    value="<?= htmlspecialchars((string) $b['id_bilancio']) ?>"
                >

                    <?= htmlspecialchars($b['azienda']) ?>
                    -
                    <?= htmlspecialchars($b['data_creazione']) ?>

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

            <option value="">

                Seleziona revisore

            </option>

            <?php foreach($revisori as $r) : ?>

                <option
                    value="<?= htmlspecialchars((string) $r['id_utente']) ?>"
                >

                    <?= htmlspecialchars($r['username']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <button
        type="submit"
        class="btn btn-success"
    >

        Assegna Revisore

    </button>

</form>


<hr>


<!-- REVISIONI ASSEGNATE -->

<h3>

    Revisioni Assegnate

</h3>


<?php if(empty($revisioni)) : ?>

    <div class="alert alert-info">

        Non ci sono ancora revisioni assegnate.

    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>ID</th>
                <th>Data Bilancio</th>
                <th>Azienda</th>
                <th>Revisore</th>
                <th>Stato</th>
                <th>Azioni</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($revisioni as $r) : ?>

            <tr>

                <td>

                    <?= htmlspecialchars((string) $r->id_revisione) ?>

                </td>

                <td>
                    <?= htmlspecialchars((string) $r->data_bilancio) ?>
                    
                </td>

                <td>

                    <?= htmlspecialchars($r->azienda ?? '') ?>

                </td>

                <td>

                    <?= htmlspecialchars($r->username ?? '') ?>

                </td>

                <td>

                    <?php if($r->stato === 'conclusa') : ?>

                        <span class="badge bg-success">

                            Conclusa

                        </span>

                    <?php else : ?>

                        <span class="badge bg-warning text-dark">

                            <?= htmlspecialchars(
                                ucfirst($r->stato ?? 'assegnata')
                            ) ?>

                        </span>

                    <?php endif; ?>

                </td>

                <td>

                    <a
                        href="/esg-balance/revisioni.php?action=dettaglioAdmin&id=<?= htmlspecialchars((string) $r->id_bilancio) ?>"
                        class="btn btn-info btn-sm"
                    >

                        Dettaglio Revisione

                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>


<?php

include __DIR__ . '/../partials/footer.php';

?>