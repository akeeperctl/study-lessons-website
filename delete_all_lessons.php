<?php
session_start();
require_once 'db.php';

// Проверка наличия сессии пользователя
if (!isset($_SESSION['username']) || !isset($_SESSION['userId'])) 
{
  // Перенаправление на страницу входа, если пользователь не авторизован
  header("Location: index.php"); 
  exit();
}

// Получение имени пользователя из сессии
$userId = $_SESSION['userId'];

// Подготовка SQL-запроса для удаления занятий конкретного пользователя 
$stmt = $pdo->prepare("SELECT * FROM ".$db_user_lessons_table_name." WHERE user_id = ".$userId."");
$stmt->execute();
$user_lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Подготовка SQL-запроса для удаления занятий конкретного пользователя
$stmt = $pdo->prepare("SELECT * FROM ".$db_lessons_table_name."");
$stmt->execute();
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

//ДИКИЙ O(n^2) УЖАС
foreach ($lessons as $key => $lesson)
{
  foreach ($user_lessons as $key => $user_lesson)
  {
	  $user_lesson_id = $user_lesson['lesson_id'];
	  $tab_lesson_id = $lesson['id'];
	  
	  //Удаление урока из таблицы всех уроков
	  if($tab_lesson_id == $user_lesson_id)
	  {
		  $stmt = $pdo->prepare("DELETE FROM ".$db_lessons_table_name." 
		  WHERE id = ".$tab_lesson_id."");
		  
		  $stmt->execute();
	  }
	
	//Удаление урока из таблицы уроков юзера
	$stmt = $pdo->prepare("DELETE FROM ".$db_user_lessons_table_name." 
	WHERE lesson_id = ".$tab_lesson_id." 
	AND user_id = ".$userId."");
	
	$stmt->execute();
  }
}

header("Location: dashboard.php?delete_all_success=1");
exit();