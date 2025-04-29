<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

require_once "../../database.php";

$sql = "SELECT * FROM user
        WHERE reset_token_hash = '$token_hash'";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

if ($user === null) {
    die("utilisateur avec ce email n'existe pas");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token est expiré");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Réinitialiser mot passe</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Réinitialiser mot passe</h1>

    <form method="post" action="process_reset_password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">Nouveau mot passe</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Confirmer mot passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation">

        <button>Envoyer</button>
    </form>

</body>
</html>