<?php
//Check session variable
require_once("friendclass.php");
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
}
if ($_SESSION["user"] == null) {
    header("location: index.php");
}
$user = $_SESSION["user"];

//Check if the post request is sent then perform the self post function
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $user->addfriend($id);
    header("location: friendadd.php");
}

require_once("settings.php");
try {
    $conn = @new mysqli($host, $username, $password, $dbname);

    $friends_per_page = 5;

    $query = "SELECT * FROM friends";
    $result = $conn->query($query);
    $count = $result->num_rows - count($user->getFriends()) - 1;

    $page_count = ceil($count / $friends_per_page);

    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    $page_first_result = ($page - 1) * $friends_per_page;
    $query = "SELECT * FROM friends WHERE ";
    $query .= "friend_id != '" . $user->getUser()["friend_id"] . "'";
    foreach ($user->getFriends() as $f) {
        $query .= " AND friend_id != '" . $f["id"] . "'";
    }
    $query .= " LIMIT $page_first_result, $friends_per_page";
    $result = $conn->query($query);
} catch (Exception $e) {
    echo "<p>" . $e->getMessage() . "</p>";
    exit;
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
            <h2 class="text-center">My Friend System</h2>
            <h2 class="text-center">
                <?= $user->getUser()["profile_name"] . "'s Add Friend Page" ?>
            </h2>
            <h2 class="text-center">
                <?= "Total number of friends is " . $user->friendCount() ?>
            </h2>
            <form class="center" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="center list">

                <!-- Creating a list of friend with while loop -->
                    <?php while ($friend = $result->fetch_assoc()): ?>
                        <div class="row">
                            <div>
                                <p>
                                    <?= $friend["profile_name"]; ?>
                                </p>
                            </div>
                            <?php
                            $query = "SELECT COUNT(a.mutual_friend) AS mutual FROM
                            (
                            SELECT friend_id2 mutual_friend FROM myfriends WHERE friend_id1 = " . $user->getUser()["friend_id"] . "
                                UNION 
                            SELECT friend_id1 mutual_friend FROM myfriends WHERE friend_id2 = " . $user->getUser()["friend_id"] . "
                            ) AS a
                            JOIN  
                            (
                            SELECT friend_id2 mutual_friend FROM myfriends WHERE friend_id1 = " . $friend["friend_id"] . "
                                UNION 
                            SELECT friend_id1 mutual_friend FROM myfriends WHERE friend_id2 = " . $friend["friend_id"] . "
                            ) AS b 
                            ON a.mutual_friend = b.mutual_friend";
                            $mutual = $conn->query($query);
                            echo "<div><p>" . $mutual->fetch_assoc()["mutual"] . " mutual friends</p></div>";
                            ?>
                            <button type="submit" name="id" value="<?= $friend["friend_id"] ?>">Add as friend</button>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Next/Previous page buttons -->

                <div class="center pgbtn-container">
                    <?php if ($page > 1): ?>
                        <div class="previous btn">
                            <a href="friendadd.php?page=<?= $page - 1 ?>">Previous</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($page < $page_count): ?>
                        <div class="next btn">
                            <a href="friendadd.php?page=<?= $page + 1 ?>">Next</a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <?php
    ?>
</body>

</html>