<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Nguyen The Vinh" />
    <title>Assignment 1</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <nav>
        <ul>
            <li
                class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "index.php" ? "liactive" : ""; ?>">
                <a href="index.php">Home</a>
            </li>
        </ul>
    </nav>
    <div class="">
        <h1>My Friend System Registration Page</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['$PHP_SELF']); ?>" method="post">
            <label for="email">Email: </label>
            <input id="email" name="email" />
            <label for="pname">Profile Name: </label>
            <input id="pname" name="pname" />
            <label for="p1">Password: </label>
            <input id="p1" type="password" name="p1" />
            <label for="p2">Confirm Password: </label>
            <input id="p2" type="password" name="p2" />
            <div>
                <button type="submit">Register</button>
                <button type="reset">Clear</button>
            </div>
        </form>
        <div class="">
            <div><a href="#">Sign-Up</a></div>
            <div><a href="#">Log-In</a></div>
            <div><a href="#">About</a></div>
        </div>
    </div>
    <?php
    require_once("settings.php");
    require_once("functions/utils.php");
    if (!$_SERVER["REQUEST_METHOD"] == "POST") {
        return;
    }
    $email = trim($_POST["email"]);
    $pname = trim($_POST["pname"]);
    $p1 = trim($_POST["p1"]);
    $p2 = trim($_POST["p1"]);
    $err = rValidate($email, $pname, $p1, $p2);
    if (count($err) > 0) {
        foreach ($err as $errm) {
            echo $errm;
        }
        return;
    }


    $conn = new mysqli("feenix-mariadb.swin.edu.au", $username, $password, $dbname);
    if ($conn->connect_error)
        die("Unable to connect");
    try {
        $query = "SELECT * FROM `friends` WHERE `friend_email`='$email' LIMIT 1";
        $result = $conn->query($query);
        $nodata = true;
        if ($result->num_rows > 0) {
            $nodata = false;
        }
        if ($nodata) {
            foreach ($friendsdata as $data) {
                $query = "INSERT INTO `friends` (`friend_email`, `password`, `profile_name`, `date_started`, `num_of_friends`) 
                VALUES ('" . $data['friend_email'] . "','" . $data['password'] . "','" . $data['profile_name'] . "','" .
                    $data['date_started'] . "','" . $data['num_of_friends'] . "');";
                $conn->query($query);
            }
            foreach ($myfriendsdata as $data) {
                $query = "INSERT INTO `myfriends` (`friend_id1`, `friend_id2`) 
                VALUES ('" . $data['friend_id1'] . "','" . $data['friend_id2'] . "');";
                $conn->query($query);
            }
        }
        echo "<p>Tables successfully created and populated</p>";
    } catch (Exception $e) {
        echo "<p>$e</p>";
    }
    $conn->close();
    ?>
</body>

</html>