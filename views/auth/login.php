<?php

include __DIR__ . '/../partials/header.php';

?>

<h2 class="mb-4">
    Login
</h2>

<?php if(isset($errore)) : ?>

    <div class="alert alert-danger">

        <?= htmlspecialchars($errore) ?>

    </div>

<?php endif; ?>

<form method="POST">

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
            autocomplete="current-password"
            required
        >

    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Accedi
    </button>

</form>

<p class="mt-3">

    Non hai un account?

    <a href="/esg-balance/register.php">
        Registrati
    </a>

</p>

<?php

include __DIR__ . '/../partials/footer.php';

?>