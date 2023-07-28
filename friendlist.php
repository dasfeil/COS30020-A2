<?php
require_once("friendclass.php");
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
}
if ($_SESSION["user"] == null) {
    header("location: index.php");
}
$user = $_SESSION["user"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $user->unfriend($id);
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
        <h2>My Friend System</h2>
        <?php
        echo "<h2>" . $user->getUser()["profile_name"] . "'s Friend List Page</h2>";
        echo "<h2>Total number of friends is " . $user->friendCount() . "</h2>";
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php foreach ($user->getFriends() as $f): ?>
                <div>
                    <?= $f["profile_name"]; ?>
                    <button type="submit" name="id" value="<?= $f["id"] ?>">Unfriend</button>
                </div>
            <?php endforeach; ?>
        </form>
        <div class="">
            <div><a href="friendadd.php">Add Friends</a></div>
            <div><a href="logout.php">Logout</a></div>
        </div>
    </div>
    <?php
    ?>
</body>

</html>