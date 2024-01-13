<?php
// Inicie a sessão (se ainda não estiver iniciada)
session_start();

// Destrua a sessão
session_destroy();

// Redirecione o usuádrido de volta para a página de login
header("Location: login.php");
exit();
?>
