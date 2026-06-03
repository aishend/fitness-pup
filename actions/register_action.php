<?php
  session_start();

  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
      header('Location: ../register.php');
      exit;
  }

  require_once '../database/connection.php';
  require_once '../database/users.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $dbh = getDatabaseConnection();

      $name = trim($_POST['name']);
      $username = trim($_POST['username']);
      $email = trim($_POST['email']);
      $password = $_POST['password'];

      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $role = 'member';

      try {
          insertUser($dbh, $name, $username, $email, $hashedPassword, $role);

          $_SESSION['success_message'] = "Account created successfully! You can now login.";
          header('Location: ../login.php');
          exit;

      } catch (PDOException $e) {
          if ($e->getCode() == 23000) {
              $_SESSION['error_message'] = "Username or Email is already taken.";
          } else {
              $_SESSION['error_message'] = "An error occurred: " . $e->getMessage();
          }

          header('Location: ../register.php');
          exit;
      }
  } else {
      header('Location: ../register.php');
      exit;
  }
?>
