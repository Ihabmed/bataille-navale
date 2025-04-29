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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container" style="background-color: white;">
        <?php
            if (isset($_POST["submit"])) {
                $email = $_POST["email"];
                $password = $_POST["password"];
                $username = $_POST["username"];
                $repeat_password = $_POST["confirm_password"];
                
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
     
                $errors = array();
                
                if (empty($email) OR empty($username) OR empty($password) OR empty($username)) {
                 array_push($errors,"Tous les champs sont obligatoires");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 array_push($errors, "L'e-mail n'est pas valide");
                }
                if (strlen($password)<8) {
                 array_push($errors,"Le mot de passe doit comporter au moins 8 caractères");
                }
                if ($password != $repeat_password)
                {
                 array_push($errors,"Le mot de passe ne correspond pas");
                }
                require_once "../database.php";
                $sql = "SELECT * FROM user WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);

                if ($rowCount>0) {
                    array_push($errors,"L'email existe déjà !");
                }

                $sql = "SELECT * FROM user WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount>0) {
                 array_push($errors,"nom existe déjà !");
                }
                if (count($errors)>0) {
                 foreach ($errors as  $error) {
                     echo "<div class='alert alert-danger'>$error</div>";
                 }
                }else{
                 $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
                 $stmt = mysqli_stmt_init($conn);
                 $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                 if ($prepareStmt) {
                     mysqli_stmt_bind_param($stmt,"sss", $username, $email, $passwordHash);
                     try {
                     mysqli_stmt_execute($stmt);
                     } catch (Exception $e)
                     {
                        echo $e;
                     }
                     echo "<div class='alert alert-success'>Vous êtes inscrit avec succès.</div>";
                    header("Location: login.php");
                    die();  
                 }else{
                     die("Quelque chose s'est mal passé");
                 }
                }
               
     
            }
        ?>
        <form  action="registration.php" method="post">
            <div class="form-group">
                <label for="username" style="margin-bottom: 10px; font-weight: bold;">Nom:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="saisir nom" required>
            </div>
            <div class="form-group">
                <label for="email" style="margin-bottom: 10px; font-weight: bold;">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="saisir email" required>
            </div>
            <div class="form-group">
                <label for="password" style="margin-bottom: 10px; font-weight: bold;">Mot passe:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="saisir mot passe" required>
            </div>
            <div class="form-group">
                <label for="password" style="margin-bottom: 10px; font-weight: bold;">Confirmer mot passe:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="resaissir mot passe" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Inscrir" name="submit" style="width: 500px; height: 50px;">
            </div>
            <div style="margin-top:25px; margin-left:100px;"><p>vous avez déjà un compte ? <a href="login.php">se connecter</a></p></div>
        </form>
    </div>
</body>
</html>