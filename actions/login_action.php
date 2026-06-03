<?php
  session_start();

  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
      header('Location: ../login.php');
      exit;
  }

  require_once '../database/connection.php';
  require_once '../database/users.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $dbh = getDatabaseConnection();

      $loginInput = trim($_POST['login_input']);
      $password = $_POST['password'];

      try {
          $user = getUserByUsernameOrEmail($dbh, $loginInput);
          if ($user && password_verify($password, $user['password'])) {
              $_SESSION['userID'] = $user['userID'];
              $_SESSION['username'] = $user['username'];
              $_SESSION['name'] = $user['name'];
              $_SESSION['role'] = $user['role'];

              if ($_SESSION['role'] === 'admin') {
                  header('Location: ../admin_dashboard.php');
              } else {
                  header('Location: ../index.php');
              }
              exit;

          } else {
              $_SESSION['error_message'] = "Invalid username/email or password.";
              header('Location: ../login.php');
              exit;
          }

      } catch (PDOException $e) {
          $_SESSION['error_message'] = "An error occurred during login.";
          header('Location: ../login.php');
          exit;
      }
  } else {
      header('Location: ../login.php');
      exit;
  }
?>
