<?php
include __DIR__ . '/../partials/header.php';
?>

<h2 class="mb-4">
    Login
</h2>

<?php if(isset($errore)) : ?>

    <div class="alert alert-danger">

        <?= $errore ?>

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

    <button
        type="submit"
        class="btn btn-primary"
    >
        Accedi
    </button>

</form>

<?php
include __DIR__ . '/../partials/footer.php';
?>