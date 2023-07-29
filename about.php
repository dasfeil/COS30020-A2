<?php
session_start();
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
            <h2 class="text-center">About</h2>
            <div class="about-content center">
                <p>All tasks attempted/completed</p>
                <p>No special function attempted for the site</p>
                <p>I had the most trouble with the part where we get mutual friends</p>
                <p>If I were given the same project with less restriction, i.e., being able to use javascript I would
                    try to create a more visually appealing website</p>
                <p>A small addition, a navigation bar that change links based on session variable</p>
                <p>You need to login to access friendlist and friendadd page</p>
                <p><a href="friendlist.php"><span style="color: white;">Friend List</span></a></p>
                <p><a href="friendadd.php"><span style="color: white;">Friend Add</span></a></p>
                <p><a href="index.php"><span style="color: white;">Home Page</span></a></p>
                <img class="d-img" src="images/discussion.png" />
            </div>

        </div>
    </div>
    <?php
    ?>
</body>

</html>