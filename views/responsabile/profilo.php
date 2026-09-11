<?php

/** @var Responsabile|null $profilo */

include __DIR__ . '/../partials/header.php';

$backUrl = '/esg-balance/index.php';

include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Il mio profilo

</h2>

<?php if(isset($_SESSION['errore_cv'])) : ?>

    <div class="alert alert-danger">

        <?= htmlspecialchars($_SESSION['errore_cv']) ?>

    </div>

    <?php unset($_SESSION['errore_cv']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['successo_cv'])) : ?>

    <div class="alert alert-success">

        <?= htmlspecialchars($_SESSION['successo_cv']) ?>

    </div>

    <?php unset($_SESSION['successo_cv']); ?>

<?php endif; ?>


<div class="card shadow-sm">

    <div class="card-body">

        <h5 class="card-title">

            Curriculum Vitae

        </h5>

        <?php if(
            $profilo &&
            $profilo->hasCv()
        ) : ?>

            <div class="alert alert-success">

                Curriculum Vitae presente.

            </div>

            <div class="mb-4">

                <a
                    href="/esg-balance/<?= htmlspecialchars($profilo->cv_pdf) ?>"
                    target="_blank"
                    class="btn btn-primary"
                >

                    Visualizza CV

                </a>

            </div>

            <h6>

                Sostituisci Curriculum Vitae

            </h6>

        <?php else : ?>

            <div class="alert alert-warning">

                Nessun Curriculum Vitae caricato.

            </div>

            <h6>

                Carica Curriculum Vitae

            </h6>

        <?php endif; ?>


        <form
            method="POST"
            action="/esg-balance/responsabile.php?action=upload"
            enctype="multipart/form-data"
            class="mb-4"
        >

            <div class="mb-3">

                <label class="form-label">

                    Curriculum Vitae PDF

                </label>

                <input
                    type="file"
                    name="cv_pdf"
                    class="form-control"
                    accept="application/pdf,.pdf"
                    required
                >

                <div class="form-text">

                    È consentito esclusivamente il formato PDF.
                    Dimensione massima: 5 MB.

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-success"
            >

                <?php if(
                    $profilo &&
                    $profilo->hasCv()
                ) : ?>

                    Sostituisci CV

                <?php else : ?>

                    Carica CV

                <?php endif; ?>

            </button>

        </form>


        <?php if(
            $profilo &&
            $profilo->hasCv()
        ) : ?>

            <hr>

            <form
                method="POST"
                action="/esg-balance/responsabile.php?action=delete"
                onsubmit="return confirm('Vuoi eliminare il Curriculum Vitae?');"
            >

                <button
                    type="submit"
                    class="btn btn-danger"
                >

                    Elimina CV

                </button>

            </form>

        <?php endif; ?>

    </div>

</div>

<?php

include __DIR__ . '/../partials/footer.php';

?>