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

// Подготовка SQL-запроса для получения занятий пользователя
$stmt = $pdo->prepare("SELECT 
".$db_users_table_name.".id, 
".$db_lessons_table_name.".id, 
".$db_lessons_table_name.".lesson_name, 
".$db_lessons_table_name.".teacher_name, 
".$db_lessons_table_name.".lesson_date
FROM ".$db_lessons_table_name."
JOIN ".$db_user_lessons_table_name." ON ".$db_lessons_table_name.".id = ".$db_user_lessons_table_name.".lesson_id
JOIN ".$db_users_table_name." ON ".$db_users_table_name.".id = ".$db_user_lessons_table_name.".user_id
WHERE ".$db_users_table_name.".username = :username");

$stmt->execute([':username' => $username]);

// Получение результатов запроса в виде ассоциативного массива
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

//debug draw
// echo "<pre>";
// print_r($lessons);
// echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/styles_generic.css">
  <title>Личный кабинет</title>
</head>
<body>
  <div class="container">
    <h2>Личный кабинет</h2>
    <h3>Занятия:</h3>
    <?php 
    // Проверка наличия занятий
	if (count($lessons) > 0)
    {
    ?>
      <table>
        <tr>
          <th>№</th>
		  <th>Дата занятия</th>
          <th>Название занятия</th>
          <th>Имя преподавателя</th>
		  <th>
			  <form method="POST" action="delete_all_lessons.php">
				<button type="submit">Удалить всё</button>
			  </form>
		  </th>
        </tr>
        <?php 
		// Вывод информации о занятиях в таблицу
		foreach ($lessons as $key => $lesson)
        {
        ?>
          <tr>
            <td><?php echo $key + 1; ?></td>
			<td><?php echo $lesson['lesson_date']; ?></td>
            <td><?php echo $lesson['lesson_name']; ?></td>
            <td><?php echo $lesson['teacher_name']; ?></td>
			<td>
			  <form method="POST" action="delete_lesson.php">			
				<input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
				<button type="submit">Удалить</button>
			  </form>
			</td>
          </tr>
        <?php
        }
        ?>
      </table>
    <?php
    }
    else
    {
    ?>
      <p>У вас пока нет занятий.</p>
    <?php
    }
    ?>

    <h3>Добавить занятие:</h3>
	
    <form method="POST" action="add_lesson.php">
	  <label for="lesson_date">Дата занятия:</label>
      <input type="text" name="lesson_date" placeholder="Дата занятия" required><br>
	  
      <label for="lesson_name">Название занятия:</label>
      <input type="text" name="lesson_name" placeholder="Название занятия" required><br>
	  
      <label for="teacher_name">Имя преподавателя:</label>
      <input type="text" name="teacher_name" placeholder="Имя преподавателя" required><br>
	  
      <button type="submit">Добавить</button>
    </form>

	<form method="POST" action="logout.php">
	  <button type="submit">Выйти</button>
    </form>
  </div>
</body>
</html>
