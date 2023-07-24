<?php
class Friend {
    private $id;
    private $email;
    private $pname;
    private $password;
    private $date;
    private $friendnum;
    private $friends;

    public function __construct($aId, $aEmail, $aPassword, $aPname, $aDate, $aFriendnum) {
        $this->id = $aId;
        $this->email = $aEmail;
        $this->password = $aPassword;
        $this->pname = $aPname;
        $this->date = $aDate;
        $this->friendnum = $aFriendnum;
        $this->friends = array();
    }
}
?>