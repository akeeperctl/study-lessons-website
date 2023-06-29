<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) 
{
  header("Location: index.php");
  exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  $lessonName = $_POST['lesson_name'];
  $teacherName = $_POST['teacher_name'];
  $lessonDate = $_POST['lesson_date'];
  $lessonDate = date('Y-m-d', strtotime($lessonDate));

  // Подготовка SQL-запроса для добавления занятия
  $stmt = $pdo->prepare("INSERT INTO ".$db_lessons_table_name." (lesson_name, teacher_name, lesson_date) VALUES (?, ?, ?)");
  $stmt->execute([$lessonName, $teacherName, $lessonDate]);

  // Получение ID добавленного занятия
  $lessonId = $pdo->lastInsertId();

  // Связывание занятия с пользователем
  $stmt = $pdo->prepare("INSERT INTO ".$db_user_lessons_table_name." (user_id, lesson_id) VALUES ((SELECT id FROM ".$db_users_table_name." WHERE username = :username), :lesson_id)");
  $stmt->execute([':username' => $username, ':lesson_id' => $lessonId]);

  // Перенаправление обратно на страницу личного кабинета
  header("Location: dashboard.php");
  exit();
}
?>
