<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container" style="background-color: white; margin-top: 100px;">
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "../database.php";
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    $_SESSION["user_id"] = $user["id"];
                    header("Location: ../index.php");
                    die();
                }else{
                    echo "<div class='alert alert-danger'>Le mot de passe ne correspond pas</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>L'e-mail ne correspond pas</div>";
            }
        }
        ?>
      <form action="login.php" method="post">
        <div class="form-group">
            <label for="email" style="margin-bottom: 10px; font-weight: bold;">Email:</label>
            <input type="email" placeholder="saisir email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="password" style="margin-bottom: 10px; font-weight: bold;">Mot passe:</label>
            <a href="oublie_motpasse/forget_password.php" style="margin-left: 275px;">mot passe oublié ?</a>
            <input type="password" placeholder="saisir mot passe" name="password" class="form-control" style="margin-bottom: 10px;">
        </div>
        <div class="form-btn">
            <input type="submit" value="Connecter" name="login" class="btn btn-primary" style="margin-top: 20px; height: 50px; width: 500px;">
        </div>
      </form>
     <div style="margin-top:25px; margin-left: 125px;"><p>vous n'avez pas un compte <a href="registration.php">s'inscrire</a></p></div>
    </div>
</body>
</html>