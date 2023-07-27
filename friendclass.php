<?php
class Friend
{
    private $user;
    private $friends;
    private $conn;

    public function __construct($aEmail)
    {
        require_once("settings.php");
        $this->conn = new mysqli($host, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Unable to connect");
        }
        $this->friends = array();
        try {
            $query = "SELECT * FROM `friends` WHERE friend_email = '$aEmail'";
            $this->user = $this->conn->query($query)->fetch_assoc();
            $this->friends = $this->setupFriends();
        } catch (Exception $e) {
            echo "<p>$e</p>";
        }
    }

    public function setupFriends()
    {
        $friendids = array();
        $id = $this->user["id"];
        $query = "SELECT * FROM `myfriends` WHERE friend_id1 = '$id' OR WHERE friend_id2 = '$id'";
        $result = $this->conn->query($query)->fetch_assoc();
        while ($result) {
            if (strcmp($result["friend_id1"], $id) == 0) {
                $friendids[] = $result["friend_id1"];
            } else {
                $friendids[] = $result["friend_id2"];
            }
        }
        $arr = array();
        foreach ($friendids as $friendid) {
            $query = "SELECT * FROM 'friends' WHERE 'friend_id' = $friendid LIMIT 1";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            $arr[] = array('id'=>$data["friend_id"], 'email'=>$data["friend_email"], 'profile_name'=>$data["profile_name"]);
        }
        return $arr;
    }
}
?>