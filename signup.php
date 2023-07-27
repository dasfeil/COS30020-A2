<?php
session_start();
if (!isset($_SESSION["logged"]) || $_SESSION["logged"] = false) {
    $_SESSION["logged"] = false;
    $_SESSION["user"] = array();
} else {
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
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="email">Email: </label>
            <input id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" /> <br />
            <label for="pname">Profile Name: </label>
            <input id="pname" name="pname" value="<?php echo isset($_POST['pname']) ? $_POST['pname'] : ''; ?>" /> <br />
            <label for="p1">Password: </label>
            <input id="p1" type="password" name="p1" value="<?php echo isset($_POST['p1']) ? $_POST['p1'] : ''; ?>" />
            <br />
            <label for="p2">Confirm Password: </label>
            <input id="p2" type="password" name="p2" value="<?php echo isset($_POST['p2']) ? $_POST['p2'] : ''; ?>" />
            <br />
            <div>
                <button type="submit">Register</button>
                <button type="reset">Clear</button>
            </div>
        </form>
        <div class="">
            <div><a href="index.php">Home</a></div>
        </div>
    </div>
    <?php
    require_once("settings.php");
    require_once("functions/utils.php");
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
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
    if ($conn->connect_errno)
        die("Unable to connect");
    try {
        $query = "SELECT * FROM `friends` WHERE `friend_email`='$email' LIMIT 1";
        $result = $conn->query($query);
        $nodata = true;
        if ($result->num_rows > 0) {
            $nodata = false;
        }
        if ($nodata) {
            $query = "INSERT INTO `friends` VALUE (0, '$email', '$p1', '$pname', '" . date("Y-m-d") . "', 0)";
            $conn->query($query);
            $user = array($email, $pname);
            $_SESSION["logged"] = true;
            $_SESSION["user"] = $user;
            header("location: index.php");
        } else {
            echo "<p>This email is already taken</p>";
        }
    } catch (Exception $e) {
        echo "<p>$e</p>";
    }
    $conn->close();
    ?>
</body>

</html>