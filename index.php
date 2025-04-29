<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: compte/login.php");
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bataille Navale</title>
    <link rel="icon" type="image/x-icon" href="public/battleship_icon.png">
    </link>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
    <header style="background-color: white; height: 80px; width: 100%; position: fixed; top: 0; left: 0; z-index: 1; display: flex;">
        <img src="public/battleship_icon.png" alt="Battleship Icon" style="width: 60px; height: 60px; margin-left: 20px; margin-top: 10px;">
        <h1 style="color: navy; font-size: 50px; margin-left: 20px; margin-top: 10px; margin-right: 40px;">Bataille Navale</h1>

        <a href="index.php" style="font-size: 25px; padding: 30px 40px; background-color: navy; text-decoration: none; color: white;">Accueil</a>

        <a href="classement.php" style="font-size: 25px; padding: 30px 40px; text-decoration: none; color: black;">Classement</a>

        <?php
        $id = $_SESSION["user_id"];

        require_once "database.php";

        $sql_classement = "SELECT username, COUNT(id) as total_victoires FROM user, partie where ((id = user_1 or id = user_2) and id = gagne and id = $id)";
        $result_classement = mysqli_query($conn, $sql_classement);
        $classement = mysqli_fetch_array($result_classement, MYSQLI_ASSOC);

        echo "<h3 style='margin-left: 250px; margin-top: 30px;'>niveau : " . $classement['total_victoires'] . "</h3>";
        ?>

        <div id="profile_pic" class="dropdown active">
            <img class="dropbtn" style="width: 50px; margin-left: -10px;" src="public/profile.svg" alt="photo of profile">
            <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="compte/setting.php">Paramètres</a>
                <a href="compte/logout.php">Log out</a>
            </div>
        </div>

    </header>

    <main>
        <h1 style="margin-top: 250px; margin-left: 550px;">Bienvenue <?php echo $classement['username']; ?></h1>

        <div id="menu-screen" class="screen active" style="margin-top: 50px; margin-left: 30px;">
            <button class="menu-button" id="vs-computer-btn"><a href="partie/game.html" style="text-decoration: none; color: white;">Jouer contre ordinateur</a></button>
        </div>

    </main>
</body>

</html>