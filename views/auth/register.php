<?php
include __DIR__ . '/../partials/header.php';
?>

<h2 class="mb-4">

    Registrazione Utente

</h2>

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
            required
        >

        <input
            type="email"
            name="emails[]"
            class="form-control mb-2"
        >

        <input
            type="email"
            name="emails[]"
            class="form-control"
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

            <option value="responsabile">

                Responsabile

            </option>

            <option value="revisore">

                Revisore ESG

            </option>

        </select>

    </div>

    <button class="btn btn-success">

        Registrati

    </button>

</form>

<?php
include __DIR__ . '/../partials/footer.php';
?>