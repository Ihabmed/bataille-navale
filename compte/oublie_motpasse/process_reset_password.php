<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

require_once "../../database.php";

$sql = "SELECT * FROM user
        WHERE reset_token_hash = '$token_hash'";

$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
$errors = array();

if ($user === null) {
    array_push($errors, "<div class='alert alert-danger'>token introuvable</div>");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    array_push($errors, "<div class='alert alert-danger'>le token est expiré</div>");    
}

if (strlen($_POST["password"]) < 8) {
    array_push($errors, "<div class='alert alert-danger'>Le mot de passe doit comporter au moins 8 caractères</div>");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    array_push($errors, "<div class='alert alert-danger'>Les mots de passe doivent correspondre</div>");
}


if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo $error;
    }
} else {
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE user
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = mysqli_stmt_init($conn);
$prepareStmt = mysqli_stmt_prepare($stmt,$sql);
if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"si", $password_hash, $user["id"]);
    mysqli_stmt_execute($stmt);
}

echo "Mot de passe mis à jour. Vous pouvez désormais vous connecter.";

header("Location: ../login.php");

}