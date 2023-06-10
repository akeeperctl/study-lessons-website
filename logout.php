<?php
session_start();

//Удалить все переменные сессии
session_unset();

//Удалить все данные о сессии
session_destroy();

header("Location: index.php");
exit();
?>
