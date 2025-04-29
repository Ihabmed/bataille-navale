<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body style="display:flex;">
    <header style="background-color: white; height: 80px; width: 100%; position: fixed; top: 0; left: 0; z-index: 1; display: flex;">
        <a href="index.php"><img src="public/arrow.svg" alt="an arrow" style='width: 50px; height: 50px; margin-top: 10px; margin-left: 10px;'></a>

        <?php

        session_start();

        $id = $_SESSION["user_id"];

        require_once "database.php";

        $username_sql = "SELECT username FROM user WHERE id=$id";

        $username = mysqli_query($conn, $username_sql);
        $username2 = mysqli_fetch_array($username, MYSQLI_ASSOC);

        $sql = "SELECT id, COUNT(id) as total_victoires FROM `partie`, `user` WHERE (user_1 = $id or user_2 = $id) and gagne = $id GROUP BY id";

        $sql2 = "SELECT id, COUNT(id) as total_defaites FROM `partie`, `user` WHERE (user_1 = $id or user_2 = $id) and gagne != $id GROUP BY id";

        $sql3 = "SELECT u.username as 'user_1', u1.username as 'user_2', p.temps_termine as temps_termine, u2.username as 'gagne' FROM `partie` p JOIN `user` u ON u.id = p.user_1 JOIN `user` u1 ON u1.id = p.user_2 JOIN `user` u2 ON u2.id = p.gagne WHERE p.user_1 = $id or p.user_2 = $id ORDER BY p.temps_termine DESC";

        $result = mysqli_query($conn, $sql);
        $result2 = mysqli_query($conn, $sql2);
        $result3 = mysqli_query($conn, $sql3);

        $victoires = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $defaites = mysqli_fetch_array($result2, MYSQLI_ASSOC);

        if($victoires == null)
        {
            $victoires = [];
            $victoires["total_victoires"] = 0;
        }
        if($defaites == null)
        {
            $defaites = [];
            $defaites["total_defaites"] = 0;
        }
        
        echo "<table style='margin-left: 150px; width:900px;'>";
        echo "<tr>";
        echo "<th style='width: 300px; border: 2px solid black; border-collapse: collapse; background-color: navy; color: white;'>Totale parties joué</th>";
        echo "<th style='width: 300px; text-align: center; background-color: lightgray;'> ". $victoires['total_victoires'] + $defaites['total_defaites'] . "</th>";
        echo "<th style='width: 300px; border: 2px solid black; border-collapse: collapse; background-color: navy; color: white;'>Victoires</th>";
        echo "<th style='width: 300px; text-align: center; background-color: lightgray;'>". $victoires['total_victoires'] . "</th>";
        echo "<th style='width: 300px; border: 2px solid black; border-collapse: collapse; background-color: navy; color: white;'>Defaites</th>";
        echo "<th style='width: 300px; text-align: center; background-color: lightgray;'>". $defaites['total_defaites'] . "</th>";
        echo "<th style='width: 300px; border: 2px solid black; border-collapse: collapse; background-color: navy; color: white;'>Statistique</th>";


        if ($victoires['total_victoires'] != 0)
        {
            echo "<th style='width: 300px; text-align: center; background-color: lightgray;'>" . intval($victoires['total_victoires'] * 100 / ($victoires['total_victoires'] + $defaites['total_defaites'])) . " %</th>";
        }
        else
        {
            echo "<th style='width: 300px; text-align: center; background-color: lightgray;'>0 %</th>";
        }
        echo "</tr>";

        echo "</table'>";
        ?>
    </header>
    <div>
        <?php
        echo "<table style='padding: 10px; margin-top: 100px; margin-left: -650px;'>";
        echo "<caption><h2>Historique des parties</h2></caption>";
        echo "<tr>";
        echo "<th style='width: 150px; border: 2px solid black; border-collapse: collapse; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; background-color: navy; color: white;'>adversaire</th>";
        echo "<th style='width: 150px; border: 2px solid black; border-collapse: collapse; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; background-color: navy; color: white;'>Temps</th>";
        while($user = mysqli_fetch_assoc($result3)) {
            if ($user['gagne'] == $username2['username'])
            {
                echo "<tr style='border: 2px solid; border-collapse: collapse; background-color: lightgreen;'>";
            } else
            {
                echo "<tr style='border: 2px solid; border-collapse: collapse; background-color: lightcoral;'>";
            }
            if ($user['user_1'] == $username2['username'])
            {
                echo "<td style='padding: 10px; text-align: center;'>" . $user['user_2'] . "</td>";
            } else
            {
                echo "<td style='padding: 10px; text-align: center;'>" . $user['user_1'] . "</td>";
            }
            echo "<td style='padding: 10px; text-align: center;'>" . $user['temps_termine'] . "</td>";
            echo "</tr>";   
        }
    ?>
    </div>
</body>
</html>