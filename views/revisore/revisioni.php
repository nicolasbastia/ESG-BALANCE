<?php

/** @var Revisione[] $revisioni */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Revisioni ESG Assegnate

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


<?php if(empty($revisioni)) : ?>

    <div class="alert alert-info">

        Non hai revisioni assegnate.

    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>ID Revisione</th>
                <th>Data Bilancio</th>
                <th>Azienda</th>
                <th>Stato Revisione</th>
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
                        href="/esg-balance/revisione_dettaglio.php?id=<?= htmlspecialchars((string) $r->id_bilancio) ?>"
                        class="btn btn-primary btn-sm"
                    >

                        <?php if($r->stato === 'conclusa') : ?>

                            Visualizza Revisione

                        <?php else : ?>

                            Apri Revisione

                        <?php endif; ?>

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