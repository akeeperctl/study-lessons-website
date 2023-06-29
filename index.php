<?php
session_start();
require_once 'db.php';

//сообщение при ошибке
$error_message = '';

if (isset($_SESSION['username']))
{
  header("Location: dashboard.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
  // Проверка введенных данных
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $stmt = $pdo->prepare("SELECT * FROM ".$db_users_table_name." WHERE username = :username");
  $stmt->execute([':username' => $username]);
  $user = $stmt->fetch();
  
  //print_r($user);

  //if ($user && password_verify($password, $user['password']))
  if($user && $password == $user['password'])
  {
    $_SESSION['username'] = $username;
	$_SESSION['userId'] = $user['id'];
    header("Location: dashboard.php");
    exit();
  }
  else
  {
    // Ошибка аутентификации
    $error_message = 'Неправильный логин или пароль. Попробуйте еще раз.';
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/styles_generic.css">
  <title>Вход</title>
</head>
<body>
  <div class="container">
    <form action="" method="POST">
	
      <h2>Вход в личный кабинет</h2>
      <input type="text" name="username" placeholder="Логин" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <button type="submit">Войти</button>
	  
      <?php 
	  if (!empty($error_message))
      {
      ?>
	    <!-- Отображение сообщения об ошибке -->
        <p class="error-message-type"> <?php echo $error_message; ?></p>
      <?php
      }
      ?>
	  
    </form>
    <form action="register.php" method="get">
      <button type="submit">Зарегистрироваться</button>
    </form>
  </div>
</body>
</html>
