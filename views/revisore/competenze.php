<?php

/** @var array $competenze */
/** @var array $mieCompetenze */

include __DIR__ . '/../partials/header.php';

$backUrl = '/ESG-BALANCE/index.php';
include __DIR__ . '/../partials/back_button.php';

?>

<h2 class="mb-4">

    Le mie competenze

</h2>


<!-- ========================================================= -->
<!-- AGGIUNGI COMPETENZA -->
<!-- ========================================================= -->

<div class="card mb-5">

    <div class="card-header">

        <h5 class="mb-0">

            Aggiungi competenza

        </h5>

    </div>

    <div class="card-body">

        <form
            method="POST"
            action="/esg-balance/competenza.php?action=create"
        >

            <div class="mb-3">

                <label class="form-label">

                    Competenza

                </label>

                <select
                    name="id_competenza"
                    class="form-select"
                    required
                >

                    <option value="">

                        Seleziona competenza

                    </option>

                    <?php foreach($competenze as $c) : ?>

                        <option
                            value="<?= $c['id_competenza'] ?>"
                        >

                            <?= htmlspecialchars($c['nome']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="mb-3">

                <label class="form-label">

                    Livello

                </label>

                <select
                    name="livello"
                    class="form-select"
                    required
                >

                    <option value="0">
                        0 - Nessuna esperienza
                    </option>

                    <option value="1">
                        1 - Base
                    </option>

                    <option value="2">
                        2 - Principiante
                    </option>

                    <option value="3">
                        3 - Intermedio
                    </option>

                    <option value="4">
                        4 - Avanzato
                    </option>

                    <option value="5">
                        5 - Esperto
                    </option>

                </select>

            </div>


            <button
                type="submit"
                class="btn btn-success"
            >

                Aggiungi competenza

            </button>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- COMPETENZE POSSEDUTE -->
<!-- ========================================================= -->

<h3 class="mb-3">

    Competenze dichiarate

</h3>


<?php if(empty($mieCompetenze)) : ?>

    <div class="alert alert-info">

        Non hai ancora dichiarato nessuna competenza.

    </div>

<?php else : ?>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>
                    Competenza
                </th>

                <th>
                    Livello
                </th>

                <th>
                    Azioni
                </th>

            </tr>

        </thead>

        <tbody>

        <?php foreach($mieCompetenze as $c) : ?>

            <tr>

                <td>

                    <?= htmlspecialchars($c['nome']) ?>

                </td>

                <td>

                    <span class="badge bg-primary">

                        <?= $c['livello'] ?> / 5

                    </span>

                </td>

                <td>

                    <!-- MODIFICA LIVELLO -->

                    <form
                        method="POST"
                        action="/esg-balance/competenza.php?action=update"
                        class="d-inline"
                    >

                        <input
                            type="hidden"
                            name="id_competenza"
                            value="<?= $c['id_competenza'] ?>"
                        >

                        <select
                            name="livello"
                            class="form-select form-select-sm d-inline-block"
                            style="width: 150px;"
                            required
                        >

                            <option
                                value="0"
                                <?= $c['livello'] == 0 ? 'selected' : '' ?>
                            >
                                0
                            </option>

                            <option
                                value="1"
                                <?= $c['livello'] == 1 ? 'selected' : '' ?>
                            >
                                1
                            </option>

                            <option
                                value="2"
                                <?= $c['livello'] == 2 ? 'selected' : '' ?>
                            >
                                2
                            </option>

                            <option
                                value="3"
                                <?= $c['livello'] == 3 ? 'selected' : '' ?>
                            >
                                3
                            </option>

                            <option
                                value="4"
                                <?= $c['livello'] == 4 ? 'selected' : '' ?>
                            >
                                4
                            </option>

                            <option
                                value="5"
                                <?= $c['livello'] == 5 ? 'selected' : '' ?>
                                >
                                5
                            </option>

                        </select>

                        <button
                            type="submit"
                            class="btn btn-primary btn-sm"
                        >

                            Modifica

                        </button>

                    </form>


                    <!-- ELIMINA -->

                    <a
                        href="/esg-balance/competenza.php?action=delete&id=<?= $c['id_competenza'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Vuoi eliminare questa competenza?');"
                    >

                        Elimina

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