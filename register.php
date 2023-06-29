<?php
// Подключение к базе данных
require_once 'db.php';

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  // Получение данных из формы
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Проверка, что имя пользователя не пустое и содержит только допустимые символы
  if (isValidUsername($username))
  {
    // Хэширование пароля
    $hashedPassword = md5($password);

    // Подготовка SQL-запроса для добавления пользователя
    $stmt = $pdo->prepare("INSERT INTO ".$db_users_table_name." (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);

    // Перенаправление пользователя после успешной регистрации
    header("Location: dashboard.php");
    exit();
  }
  else
  {
    // Ошибка в имени пользователя
    $errorMessage = 'Некорректное имя пользователя. Пожалуйста, используйте только буквы, цифры и знаки подчеркивания.';
  }
}

// Функция для проверки корректности имени пользователя
function isValidUsername($username)
{
  // Использую регулярное выражение для проверки имени пользователя
  $pattern = '/^[a-zA-Z0-9_]+$/';
  return preg_match($pattern, $username);
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/styles_generic.css">
  <link rel="stylesheet" href="css/styles_register.css">
  <title>Регистрация</title>
</head>
<body>
  <div class="form-container">
    <h2 class="form-title">Регистрация нового пользователя</h2>
    <?php 
	if (isset($errorMessage)) 
	{ 
	?>
      <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php 
	} 
	?>
    <form method="POST" action="register.php">
      <label for="username">Логин:</label>
      <input type="text" name="username" required><br>
      <label for="password">Пароль:</label>
      <input type="password" name="password" required><br>
      <button type="submit">Зарегистрироваться</button>
    </form>
  </div>
</body>
</html>
