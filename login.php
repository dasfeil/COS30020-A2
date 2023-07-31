<?php
session_start();
//Initialize session variable if it's null
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
}

//Redirect the user if the session variable is not null
if ($_SESSION["user"] !== null) {
    header("location: friendlist.php");
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
                    <label for="pass">Password: </label>
                    <input id="pass" type="password" name="pass" />
                </div>
                <?php
                include_once("settings.php");
                include_once("functions/utils.php");
                include_once("friendclass.php");

                //Check posted status
                $nopost = false;
                if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                    $nopost = true;
                }

                //Check for invalid data
                $invalid = false;
                if (!$nopost) {
                    $email = trim($_POST["email"]);
                    $pass = trim($_POST["pass"]);
                    $err = lValidate($email, $pass);
                    if (count($err) > 0) {
                        echo "<div class=\"center error\">";
                        foreach ($err as $errm) {
                            echo $errm;
                        }
                        echo "</div>";
                        $invalid = true;
                    }
                }

                //If everything is valid then query data
                if (!$nopost && !$invalid) {
                    try {
                        $conn = @new mysqli("feenix-mariadb.swin.edu.au", $username, $password, $dbname);
                        $query = "SELECT * FROM `friends` WHERE `friend_email`='$email' LIMIT 1";
                        $result = $conn->query($query);
                        $exist = false;
                        if ($result->num_rows > 0) {
                            $exist = true;
                        }
                        if (!$exist) {
                            echo "<div class=\"center error\">";
                            echo "<p>Incorrect email or password</p>";
                            echo "</div>";
                        }
                        //If user email exist in table then check password, if it's valid create a class object 
                        //for session variable and redirect user
                        else {
                            $user = $result->fetch_assoc();
                            if (strcmp($pass, $user["password"]) !== 0) {
                                echo "<div class=\"center error\">";
                                echo "<p>Incorrect email or password</p>";
                                echo "</div>";
                            } else {
                                $_SESSION["user"] = new Friend($email);
                                header("location: friendlist.php");
                            }
                        }
                    } catch (Exception $e) {
                        echo "<div class=\"center error\">";
                        echo "<p>" . $e->getMessage() . "</p>";
                        echo "</div>";
                    }
                }
                ?>
                <div class="center">
                    <button type="submit">Login</button>
                    <button type="reset" onclick="<?php unset($_POST); ?>">Clear</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>