<?php
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
}
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
        </ul>
    </nav>
    <div class="">
        <h1>My Friend System Registration Page</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="email">Email: </label>
            <input id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" />
            <br />
            <label for="pass">Password: </label>
            <input id="pass" type="password" name="pass"
                value="<?php echo isset($_POST['pass']) ? $_POST['pass'] : ''; ?>" />
            <br />
            <div>
                <button type="submit">Login</button>
                <button type="reset">Clear</button>
            </div>
        </form>
        <div class="">
            <div><a href="index.php">Home</a></div>
        </div>
    </div>
    <?php
    include_once("settings.php");
    include_once("functions/utils.php");
    include_once("friendclass.php");
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }
    $email = trim($_POST["email"]);
    $pass = trim($_POST["pass"]);
    $err = lValidate($email, $pass);
    if (count($err) > 0) {
        foreach ($err as $errm) {
            echo $errm;
        }
        return;
    }
    try {
        $conn = @new mysqli("feenix-mariadb.swin.edu.au", $username, $password, $dbname);
        $query = "SELECT * FROM `friends` WHERE `friend_email`='$email' LIMIT 1";
        $result = $conn->query($query);
        $exist = false;
        if ($result->num_rows > 0) {
            $exist = true;
        }
        if (!$exist) {
            echo "<p>Incorrect email or password</p>";
            return;
        }
        $user = $result->fetch_assoc();
        if (strcmp($pass, $user["password"]) !== 0) {
            echo "<p>Incorrect email or password</p>";
        } else {
            $_SESSION["user"] = new Friend($email);
            header("location: friendlist.php");
        }
    } catch (Exception $e) {
        echo "<p>". $e->getMessage() ."</p>";
    }
    ?>
</body>

</html>