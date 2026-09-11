<?php

session_start();

session_destroy();

header('Location: /esg-balance/login.php');

exit;

?>