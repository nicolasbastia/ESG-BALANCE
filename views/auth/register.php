<?php

include __DIR__ . '/../partials/header.php';

?>

<h2 class="mb-4">
    Registrazione Utente
</h2>

<?php if(isset($errore)) : ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($errore) ?>
    </div>

<?php endif; ?>

<form
    method="POST"
    action="register.php?action=register"
>

    <div class="mb-3">

        <label class="form-label">
            Username
        </label>

        <input
            type="text"
            name="username"
            class="form-control"
            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
            autocomplete="username"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Password
        </label>

        <input
            type="password"
            name="password"
            class="form-control"
            minlength="8"
            autocomplete="new-password"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Codice Fiscale
        </label>

        <input
            type="text"
            name="codice_fiscale"
            class="form-control"
            value="<?= htmlspecialchars($_POST['codice_fiscale'] ?? '') ?>"
            maxlength="16"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Data di nascita
        </label>

        <input
            type="date"
            name="data_nascita"
            class="form-control"
            value="<?= htmlspecialchars($_POST['data_nascita'] ?? '') ?>"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Luogo di nascita
        </label>

        <input
            type="text"
            name="luogo_nascita"
            class="form-control"
            value="<?= htmlspecialchars($_POST['luogo_nascita'] ?? '') ?>"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="emails[]"
            class="form-control mb-2"
            value="<?= htmlspecialchars($_POST['emails'][0] ?? '') ?>"
            autocomplete="email"
            required
        >

        <input
            type="email"
            name="emails[]"
            class="form-control mb-2"
            value="<?= htmlspecialchars($_POST['emails'][1] ?? '') ?>"
        >

        <input
            type="email"
            name="emails[]"
            class="form-control"
            value="<?= htmlspecialchars($_POST['emails'][2] ?? '') ?>"
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Ruolo
        </label>

        <select
            name="ruolo"
            class="form-select"
            required
        >

            <option
                value="responsabile"
                <?= ($_POST['ruolo'] ?? '') === 'responsabile' ? 'selected' : '' ?>
            >
                Responsabile
            </option>

            <option
                value="revisore"
                <?= ($_POST['ruolo'] ?? '') === 'revisore' ? 'selected' : '' ?>
            >
                Revisore ESG
            </option>

        </select>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Registrati
    </button>

</form>

<?php

include __DIR__ . '/../partials/footer.php';

?>