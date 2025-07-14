<?php
    require("../inc/connection.php");
    dbconnect();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">

<div class="login-box">
    <h1>Login</h1>

    <?php if (isset($_GET['error'])) { ?>
        <div class="text-danger text-center mb-3">
            <?php echo $_GET['error']; ?>
        </div>
    <?php } ?>

    <form action="../inc/verification.php" method="post">
        <div class="input-box">
            <label for="email"><i class="bi bi-person-fill"></i> Username</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-box">
            <label for="motdepasse"><i class="bi bi-lock-fill"></i> Password</label>
            <input type="password" id="motdepasse" name="motdepasse" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="links">
        <p>Don't have an account? <a href="index.php">Sign Up</a></p>
        <p><a href="#">Forgot Password?</a></p>
    </div>

</div>


<div class="info-text login">
      <h2 class="animation" style="--i:0; --j:20;">Welcome Back!</h2>
      <p class="animation" style="--i:0; --j:21;">Already a Member? Please Login.</p>
 </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>