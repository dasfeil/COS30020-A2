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
        <h1>My Friend System Assignment Home Page</h1>
        <p>Name: Nguyen The Vinh</p>
        <p>Student ID: 102437961</p>
        <p>Email: <a href="mailto:vinhntswh00009@fpt.edu.vn">vinhntswh00009@fpt.edu.vn</a></p>
        <p>
            I declare that this assignment is my individual work.
            I have not worked collaboratively nor have I
            copied from any other student's work or from any other source</a>
        </p>
        <div class="">
            <div><a href="signup.php">Sign-Up</a></div>
            <div><a href="#">Log-In</a></div>
            <div><a href="#">About</a></div>
        </div>
    </div>
    <?php
    require_once("settings.php");

    $conn = new mysqli("feenix-mariadb.swin.edu.au", $username, $password, $dbname);
    if ($conn->connect_error)
        die("Unable to connect");
    try {
        $query = "CREATE TABLE IF NOT EXISTS `friends` ( `friend_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
        `friend_email` VARCHAR(50) NOT NULL, `password` VARCHAR(20) NOT NULL, `profile_name` VARCHAR(30) NOT NULL,
        `date_started` DATE NOT NULL, `num_of_friends` INTEGER UNSIGNED);";
        $conn->query($query);
        $query = "CREATE TABLE IF NOT EXISTS `myfriends` ( `friend_id1` INT NOT NULL, `friend_id2` INT NOT NULL)";
        $conn->query($query);
        $query = "SELECT * FROM `friends` LIMIT 1";
        $result = $conn->query($query);
        $nodata = true;
        if ($result->num_rows > 0) {
            $nodata = false;
        }
        if ($nodata) {
            foreach ($friendsdata as $data) {
                $query = "INSERT INTO `friends` (`friend_email`, `password`, `profile_name`, `date_started`, `num_of_friends`) 
                VALUES ('". $data['friend_email'] . "','" . $data['password'] . "','" . $data['profile_name'] . "','" . 
                $data['date_started'] . "','" . $data['num_of_friends'] . "');";
                $conn->query($query);
            }
            foreach ($myfriendsdata as $data) {
                $query = "INSERT INTO `myfriends` (`friend_id1`, `friend_id2`) 
                VALUES ('". $data['friend_id1'] . "','" . $data['friend_id2'] . "');";
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