<?php
class Friend
{
    private $user;
    private $friends;
    private $conn;

    public function __construct($aEmail)
    {
        require("settings.php");
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
        $id = $this->user["friend_id"];
        $query = "SELECT * FROM `myfriends` WHERE friend_id1 = '$id' OR friend_id2 = '$id'";
        $result = $this->conn->query($query);
        while ($row = $result->fetch_assoc()) {
            if (strcmp($row["friend_id1"], $id) == 0) {
                $friendids[] = $row["friend_id2"];
            } else {
                $friendids[] = $row["friend_id1"];
            }
        }
        $arr = array();
        foreach ($friendids as $friendid) {
            $query = "SELECT * FROM `friends` WHERE friend_id = '$friendid' LIMIT 1";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            $arr[] = array('id'=>$data["friend_id"], 'email'=>$data["friend_email"], 'profile_name'=>$data["profile_name"]);
        }
        return $arr;
    }

    public function friendCount() {
        return count($this->friends);
    }

    public function getUser() {
        return $this->user;
    }

    public function getFriends() {
        return $this->friends;
    }

    private function useConn() {
        require("settings.php");
        try {
            $this->conn->ping();   
        } catch (error) {
            $this->conn = new mysqli($host, $username, $password, $dbname);
        }
        return $this->conn;
    }

    public function unfriend($fid) {
        $conn = $this->useConn();
        $id = $this->user["friend_id"];
        $query = "SELECT * FROM friends WHERE friend_id = '$fid' OR friend_id = '$id'";
        $result = $conn->query($query);
        while ($friend = $result->fetch_assoc()) {
            $friend["num_of_friends"]--;
        }
        $query = "DELETE FROM myfriends WHERE (friend_id1 = '$id' AND friend_id2 = '$fid') OR (friend_id1 = '$fid' AND friend_id2 = '$id') LIMIT 1";
        $conn->query($query);
        $temp = 0;
        foreach ($this->friends as $key=>$f) {
            if (strcmp($f["id"], $fid) == 0) {
                $temp = $key;
                break;
            }
        }
        array_splice($this->friends,$temp,1);
    }

    public function addfriend($fid) {
        $conn = $this->useConn();
        $id = $this->user["friend_id"];
        $query = "SELECT * FROM friends WHERE friend_id = '$fid' OR friend_id = '$id'";
        $result = $conn->query($query);
        while ($friend = $result->fetch_assoc()) {
            $friend["num_of_friends"]++;
            if (strcmp($friend["friend_id"],$fid) == 0) {
                $this->friends[] = array('id'=>$friend["friend_id"], 'email'=>$friend["friend_email"], 'profile_name'=>$friend["profile_name"]);
            }
        }
        $query = "INSERT INTO myfriends VALUES ($id, $fid)";
        $conn->query($query);
    }
}
?>