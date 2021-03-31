<?php
    session_start();

    include("__/php/functions.php");
    include("__/php/config.php");

    if(isset($_POST["username"]) && !empty($_POST["username"])) {
        $username = validate($_POST["username"]);
        $username = filter_var($username, FILTER_SANITIZE_STRING);
    }

    if(isset($_POST["password"]) && !empty($_POST["password"])) {
        $password = validate($_POST["password"]);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
    }

    // Searching the username from the database

    $query = "SELECT * FROM `users` WHERE `username`=:username";

	$stmt = $dbh->prepare($query);

    $stmt->bindParam('username', $username, PDO::PARAM_STR);

    $stmt->execute();

    $count = $stmt->rowCount();
    $row   = $stmt->fetch(PDO::FETCH_ASSOC);

    if($count == 1 && !empty($row)) {
        // $message = "Username was found on the database. <br />";     
        if (password_verify($password, $row["password"])) {
            // $message2 = "Password is valid. <br />";

            // Quite not sure if this works, but try to make the $session_id variable to be integer.    

            $_SESSION["id"] = filter_var($row["id"], FILTER_VALIDATE_INT);

            header('location:index.php');
        } else {
            $_SESSION["error"] = "Unfortunately, it looks like you have the wrong password. ";

            header("Location: error.php");
        }
    } else {
        $_SESSION["error"] = "Unfortunately, it looks like we can't find the username from our database. ";

        header("Location: error.php");
        // echo "username or password invalid.";
    }

?>