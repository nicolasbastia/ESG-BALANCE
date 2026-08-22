<?php

/** @var array|false $profilo */

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Il mio profilo

</h2>

<div class="card shadow-sm">

    <div class="card-body">

        <h5 class="card-title">

            Curriculum Vitae

        </h5>

        <?php if(
            $profilo &&
            !empty($profilo['cv_pdf'])
        ) : ?>

            <div class="alert alert-success">

                Curriculum Vitae presente.

            </div>

            <div class="mb-4">

                <a
                    href="/esg-balance/<?= htmlspecialchars($profilo['cv_pdf']) ?>"
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
            action="/esg-balance/profilo_responsabile.php?action=upload"
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

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-success"
            >

                <?php if(
                    $profilo &&
                    !empty($profilo['cv_pdf'])
                ) : ?>

                    Sostituisci CV

                <?php else : ?>

                    Carica CV

                <?php endif; ?>

            </button>

        </form>

        <?php if(
            $profilo &&
            !empty($profilo['cv_pdf'])
        ) : ?>

            <hr>

            <a
                href="/esg-balance/profilo_responsabile.php?action=delete"
                class="btn btn-danger"
                onclick="return confirm('Vuoi eliminare il Curriculum Vitae?');"
            >

                Elimina CV

            </a>

        <?php endif; ?>

    </div>

</div>

<?php

include __DIR__ . '/../partials/footer.php';

?>