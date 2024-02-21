<?php
// Avvia la sessione
session_start();

// Termina la sessione
session_unset();
session_destroy();

// Reindirizza alla pagina di login
header("Location: index.php");
exit();
?>