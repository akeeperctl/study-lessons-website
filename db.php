<?php
$host = 'localhost';  // Адрес хоста базы данных (обычно localhost)
$db = 'study-db';  // Имя базы данных
$user = 'root';  // Имя пользователя базы данных
$password = '';  // Пароль для подключения к базе данных

$db_users_table_name = 'users_tab';
$db_lessons_table_name = 'lessons_tab';
$db_user_lessons_table_name = 'user_lessons_tab';

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

