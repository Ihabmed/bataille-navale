<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

require_once "../../database.php";

$sql = "UPDATE user
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = mysqli_stmt_init($conn);
$prepareStmt = mysqli_stmt_prepare($stmt,$sql);
if ($prepareStmt) {
    mysqli_stmt_bind_param($stmt,"sss", $token_hash, $expiry, $email);
    mysqli_stmt_execute($stmt);
    require_once "mailer.php";

    $mail->setFrom("medmoun.ihab1@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Cliquez <a href="localhost/Websites/papergames/compte/oublie_motpasse/reset_password.php?token=$token">ici</a> 
    pour réinitialiser votre mot passe.

    END;

    try {

        $mail->send();

    } catch (Exception $e) {

        echo "<h1 style='margin-left: 300px; margin-top: 250px;'>Message could not be sent. Mailer error: {$mail->ErrorInfo}</h1>";

    }
}

echo "<h1 style='margin-left: 300px; margin-top: 250px;'>Message sent, please check your inbox.</h1>";