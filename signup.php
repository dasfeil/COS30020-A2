<?php
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
}
if ($_SESSION["user"] !== null) {
    header("location: index.php");
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
    <div class="container center">
        <div class="content">
            <h1 class="text-center">My Friend System Registration Page</h1>
            <form class="center" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="text-input">
                    <label for="email">Email: </label>
                    <input id="email" name="email"
                        value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" />
                </div>
                <div class="text-input">
                    <label for="pname">Profile Name: </label>
                    <input id="pname" name="pname"
                        value="<?php echo isset($_POST['pname']) ? $_POST['pname'] : ''; ?>" />
                </div>
                <div class="text-input">
                    <label for="p1">Password: </label>
                    <input id="p1" type="password" name="p1"
                        value="<?php echo isset($_POST['p1']) ? $_POST['p1'] : ''; ?>" />
                </div>
                <div class="text-input">
                    <label for="p2">Confirm Password: </label>
                    <input id="p2" type="password" name="p2"
                        value="<?php echo isset($_POST['p2']) ? $_POST['p2'] : ''; ?>" />
                </div>
                <?php
                require_once("settings.php");
                require_once("functions/utils.php");
                require_once("friendclass.php");
                $nopost = false;
                if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                    $nopost = true;
                }
                $invalid = false;
                if (!$nopost) {
                    $email = trim($_POST["email"]);
                    $pname = trim($_POST["pname"]);
                    $p1 = trim($_POST["p1"]);
                    $p2 = trim($_POST["p1"]);
                    $err = rValidate($email, $pname, $p1, $p2);
                    if (count($err) > 0) {
                        echo "<div class=\"center error\">";
                        foreach ($err as $errm) {
                            echo $errm;
                        }
                        echo "</div>";
                        $invalid = true;
                    }
                }
                if (!$nopost && !$invalid) {
                    try {
                        $conn = @new mysqli("feenix-mariadb.swin.edu.au", $username, $password, $dbname);
                        $query = "SELECT * FROM `friends` WHERE `friend_email`='$email' LIMIT 1";
                        $result = $conn->query($query);
                        $nodata = true;
                        if ($result->num_rows > 0) {
                            $nodata = false;
                        }
                        if ($nodata) {
                            $query = "INSERT INTO `friends` VALUE (0, '$email', '$p1', '$pname', '" . date("Y-m-d") . "', 0)";
                            $conn->query($query);
                            $_SESSION["user"] = new Friend($email);
                            header("location: friendadd.php");
                        } else {
                            echo "<div class=\"center error\">";
                            echo "<p class=\"text-center\">This email is already taken</p>";
                            echo "</div>";

                        }
                    } catch (Exception $e) {
                        echo "<div class=\"center error\">";
                        echo "<p class=\"text-center\">" . $e->getMessage() . "</p>";
                        echo "</div>";

                    }
                }
                ?>
                <div class="center">
                    <button type="submit">Register</button>
                    <button type="reset">Clear</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>