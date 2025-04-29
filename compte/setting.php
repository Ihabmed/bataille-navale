<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <a href="../index.php"><img src="../public/arrow.svg" alt="an arrow" style='width: 50px; height: 50px; margin-left: 10px; margin-top: 10px;'></a>

    <div class="container" style="background-color: white;">
    <?php

    if(isset($_POST["Change_password"])){

        require_once "../database.php";
        session_start();

        $password = $_POST["password"];
        $password_confirmation = $_POST["password_confirmation"];

        $errors = array();
                
        if (empty($password) OR empty($password_confirmation)) {
            array_push($errors,"Tous les champs sont obligatoires");
        }

        if (strlen($password)<8) {
            array_push($errors,"Le mot de passe doit comporter au moins 8 caractères");
        }
        if ($password != $password_confirmation)
        {
            array_push($errors,"Le mot de passe ne correspond pas");
        }

        $id = $_SESSION["user_id"];

        $sql = "SELECT id FROM user
            WHERE id ='$id'";

        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($user === null) {
            array_push($errors,"operation invalide");
        }

        if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        else {

            $sql = "UPDATE user
                SET password = ?,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                WHERE id = ?";

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"si", $password_hash, $user["id"]);
                mysqli_stmt_execute($stmt);
            }
            echo "Le mot de passe a été modifié avec succès.";

            header("Location: ../index.php");
            die();
        }
    }
    ?>
    <h1 style="margin-bottom: 50px; margin-left: 60px;">Changer mot passe</h1>
    <form method="post" action="setting.php">
    <div class="form-group">
    <label for="password" style="font-weight: bold; margin-bottom: 10px;">Nouveau mot passe</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="saisir nouveau mot passe" required>
    </div>

    <div class="form-group">
    <label for="password_confirmation" style="font-weight: bold; margin-bottom: 10px;">Confirmer nouveau mot passe</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="confirmer nouveau mot passe" required>
    </div>

    <div class="form-btn">
        <input type="submit" class="btn btn-primary" value="changer" name="Change_password" style="width: 500px; height: 50px; margin-top: 20px;">
    </div>
    </form>
    </div>
</body>
</html>