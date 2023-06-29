<?php
session_start();
require_once 'db.php';

// Проверка наличия сессии пользователя
if (!isset($_SESSION['username'])) 
{
  // Перенаправление на страницу входа, если пользователь не авторизован
  header("Location: index.php"); 
  exit();
}

// Получение имени пользователя из сессии
$username = $_SESSION['username'];

// Проверка, был ли отправлен идентификатор user_lesson_id в запросе
if (isset($_POST['lesson_id'])) 
{
  $lessonId = $_POST['lesson_id'];

  // Подготовка SQL-запроса для удаления занятия пользователя
  $stmt = $pdo->prepare("DELETE FROM ".$db_user_lessons_table_name." WHERE id = :lesson_id");
  $stmt->execute([':lesson_id' => $lessonId]);
  
  $stmt = $pdo->prepare("DELETE FROM ".$db_lessons_table_name." WHERE id = :lesson_id");
  $stmt->execute([':lesson_id' => $lessonId]);

  // Перенаправление обратно на личный кабинет с сообщением об успешном удалении
  header("Location: dashboard.php?delete_success=1");
  exit();
} 
else 
{
  // Если идентификатор user_lesson_id не был отправлен, перенаправление обратно на личный кабинет с сообщением об ошибке
  header("Location: dashboard.php?delete_error=1");
  exit();
}
