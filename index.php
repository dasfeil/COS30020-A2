<?php
session_start();
//Initialize session variable
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Nguyen The Vinh" />
    <title>Assignment 2</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <nav>
        <ul>
            <li
                class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "index.php" ? "liactive" : ""; ?>">
                <a href="index.php">Home</a>
            </li>
            <?php if ($_SESSION["user"] == null): ?>
                <li
                    class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "signup.php" ? "liactive" : ""; ?>">
                    <a href="signup.php">Signup</a>
                </li>
                <li
                    class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "login.php" ? "liactive" : ""; ?>">
                    <a href="login.php">Login</a>
                </li>
            <?php else: ?>
                <li
                    class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "friendlist.php" ? "liactive" : ""; ?>">
                    <a href="friendlist.php">Friend List</a>
                </li>
                <li
                    class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "friendadd.php" ? "liactive" : ""; ?>">
                    <a href="friendadd.php">Friend Add</a>
                </li>
                <li
                    class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "logout.php" ? "liactive" : ""; ?>">
                    <a href="logout.php">Logout</a>
                </li>
            <?php endif; ?>
            <li
                class="<?php echo basename(htmlspecialchars($_SERVER["PHP_SELF"])) == "about.php" ? "liactive" : ""; ?>">
                <a href="about.php">About</a>
            </li>
        </ul>
    </nav>
    <div class="center container">
        <div class="content">
            <h1 class="text-center">My Friend System Assignment Home Page</h1>
            <div class="flex-horizontal">
                <div class="info">
                    <p>Name: Nguyen The Vinh</p>
                    <p>Student ID: 102437961</p>
                    <p>Email: <a href="mailto:vinhntswh00009@fpt.edu.vn">vinhntswh00009@fpt.edu.vn</a></p>
                    <p>
                        I declare that this assignment is my individual work.
                        I have not worked collaboratively nor have I
                        copied from any other student's work or from any other source
                    </p>
                </div>
                <div class="img-container">
                    <img class="icon" src="images/Das.png" alt="Icon" />
                </div>
            </div>

            <?php
            require_once("settings.php");
            try {
                //DB setup
                $conn = @new mysqli("feenix-mariadb.swin.edu.au", $username, $password, $dbname);
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

                //Query the database with sample data
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
                echo "<p class=\"text-center\">Tables successfully created and populated</p>";
            } catch (Exception $e) {
                echo "<div class=\"center error\">";
                echo "<p class=\"text-center\">" . $e->getMessage() . "</p>";
                echo "</div>";
            }
            ?>
        </div>

    </div>
</body>

</html>