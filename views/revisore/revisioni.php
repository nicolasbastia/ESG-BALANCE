<?php

/** @var array $revisioni */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';
?>

<h2 class="mb-4">

    Revisioni ESG Assegnate

</h2>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID Revisione</th>
            <th>Azienda</th>
            <th>Stato Bilancio</th>
            <th>Azioni</th>

        </tr>

    </thead>

    <tbody>

    <?php foreach($revisioni as $r) : ?>

        <tr>

            <td>
                <?= $r['id_revisione'] ?>
            </td>

            <td>
                <?= $r['azienda'] ?>
            </td>

            <td>

                <span class="badge bg-warning">

                    <?= $r['stato'] ?>

                </span>

            </td>

            <td>

                <a

                    href="/esg-balance/revisione_dettaglio.php?id=<?= $r['id_bilancio'] ?>"

                    class="btn btn-primary btn-sm"
                >

                    Apri Revisione

                </a>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php
include __DIR__ . '/../partials/footer.php';
?>