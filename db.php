<?php
$host = 'localhost';  // Адрес хоста базы данных (обычно localhost)
$db = 'myapp';  // Имя базы данных
$user = 'myapp_db_common_user';  // Имя пользователя базы данных
$password = 'czT_2GM9s#6KmUT';  // Пароль для подключения к базе данных

try 
{
  // Создание объекта PDO для установки соединения с базой данных
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
  // Установка режима ошибок PDO на исключения
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) 
{
  // Обработка ошибки подключения к базе данных
  echo "Ошибка подключения к базе данных: " . $e->getMessage();
  exit();
}

