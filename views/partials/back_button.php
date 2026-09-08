<?php

$backUrl = $backUrl ?? '/ESG-BALANCE/index.php';

?>

<div class="mb-4">

    <a
        href="<?= htmlspecialchars($backUrl) ?>"
        class="btn btn-outline-secondary"
    >
        ← Torna indietro
    </a>

</div>

<?php
unset($backUrl);
?>
