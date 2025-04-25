<?php
include('db_conn.php');

$alert = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['user_name'];
  $email = $_POST['user_email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    $alert = "incomplete";
  } elseif ($password !== $confirm_password) {
    $alert = "mismatch";
  } else {
    $check_email = $conn->query("SELECT * FROM signup WHERE user_email = '$email'");
    if ($check_email->num_rows > 0) {
      $alert = "exists";
    } else {
      $stmt = $conn->prepare("INSERT INTO signup (user_name, user_email, password, confirm_password) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $email, $password, $confirm_password);
      if ($stmt->execute()) {
        $alert = "success";
      }
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup Form</title>
  <link rel="stylesheet" href="css/signup.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="container">
    <div class="form registration">
      <header>Signup</header>
      <form method="POST" action="" autocomplete="off">
        <input type="text" name="user_name" placeholder="Enter your Full Name">
        <input type="text" name="user_email" placeholder="Enter your Email">
        <input type="password" name="password" placeholder="Create a Password">
        <input type="password" name="confirm_password" placeholder="Confirm your Password">
        <input type="submit" class="button" value="Signup">
      </form>
      <div class="signup">
        <span>Already have an account? <a href="login.php">Login</a></span>
      </div>
    </div>
  </div>









  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  <?php if ($alert === "incomplete"): ?>
    Swal.fire('Error', 'Please complete the signup form!', 'warning');
  <?php elseif ($alert === "mismatch"): ?>
    Swal.fire('Error', 'Your passwords do not match!', 'error');
  <?php elseif ($alert === "exists"): ?>
    Swal.fire('Oops!', 'Your email is already signed up. Please choose another one.', 'info');
  <?php elseif ($alert === "success"): ?>
    Swal.fire({
      title: 'Success!',
      text: 'Your account has been created successfully.',
      icon: 'success',
      confirmButtonText: 'Ok'
    }).then(() => {
      window.location.href = 'login.php';
    });
  <?php endif; ?>
</script>

</body>
</html>
