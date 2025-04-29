<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement</title>
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
    <header
        style="background-color: white; height: 80px; width: 100%; position: fixed; top: 0; left: 0; z-index: 1; display: flex;">
        <img src="public/battleship_icon.png" alt="Battleship Icon"
            style="width: 60px; height: 60px; margin-left: 20px; margin-top: 10px;">
        <h1 style="color: navy; font-size: 50px; margin-left: 20px; margin-top: 10px; margin-right: 40px;">Bataille
            Navale</h1>

        <a href="index.php"
            style="font-size: 25px; padding: 30px 40px; text-decoration: none; color: black;">Accueil</a>

        <a href="classement.php"
            style="font-size: 25px; padding: 30px 40px; background-color: navy; text-decoration: none; color: white;">Classement</a>

        <?php
        session_start();

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
                <a href="compte/setting.php">Setting</a>
                <a href="compte/logout.php">Log out</a>
            </div>
        </div>

    </header>
    <main>
        <?php
        if (!isset($_GET['periode']))
        {
            $_GET['periode'] = 'global';
        }

        if ($_GET["periode"] == "global") {
            $sql_classement = "SELECT username, COUNT(id) as total_victoires FROM user, partie where ((id = user_1 or id = user_2) and id = gagne) GROUP BY username ORDER BY COUNT(id) DESC";
        } else if ($_GET["periode"] == "les 15 derniers jours") {
            $sql_classement = "SELECT username, COUNT(id) as total_victoires FROM user, partie where ((id = user_1 or id = user_2) and (id = gagne) and (DATEDIFF(NOW(), temps_termine) < 16)) GROUP BY username ORDER BY COUNT(id) DESC";
        } else {
            $sql_classement = "SELECT username, COUNT(id) as total_victoires FROM user, partie where ((id = user_1 or id = user_2) and (id = gagne) and (DATEDIFF(NOW(), temps_termine) < 1)) GROUP BY username ORDER BY COUNT(id) DESC";
        }

        $result = mysqli_query($conn, $sql_classement);

        ?>

            <div id="classement">
                <table
                    style="border: 2px solid; border-collapse: collapse; margin-left: 500px; margin-top: 100px; background-color: lightgray;">
                    <caption>
                        <h1><?php if (isset($_GET['periode'])) {
                                            echo $_GET["periode"];
                                        } else {
                                            echo "1";
                                        } ?></h1>
                    </caption>
                    <tr>
                        <th
                            style="width: 100px; border: 2px solid black; border-collapse: collapse; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; background-color: navy; color: white;">
                            Nom
                        </th>
                        <th
                            style="width: 150px; border: 2px solid black; border-collapse: collapse; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; background-color: navy; color: white;">
                            Experience
                        </th>
                    </tr>
                <?php

                while ($user = mysqli_fetch_assoc($result)) {
                    echo "<tr style='border: 2px solid; border-collapse: collapse;'><td style='padding: 7px; text-align: center;'>" . $user["username"] . "</td><td style='padding: 7px; text-align: center;'>" . $user['total_victoires'] * 10 . "</td></tr>";
                }
                ?>
                </table>
                <form method="GET" action="classement.php" style="margin-left: 5px;">
                    <select id="periode" name="periode" style="width: 150px; padding: 10px; margin-left: 500px; margin-top: 20px;">
                        <option value="le dernier jour">dernier jour</option>
                        <option value="les 15 derniers jours">dernier 15 jours</option>
                        <option value="global" default>global</option>
                    </select>
                    <input type="submit" value="Changer" name="Class" style="padding: 10px; width: 150px;">
                </form>
    </main>
</body>

</html>