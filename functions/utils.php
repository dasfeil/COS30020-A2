<?php
function rValidate($email, $pname, $p1, $p2)
{
    $err = array();
    if (empty($email)) {
        array_push($err, "<p>Email cannot be empty</p>");
    }
    if (preg_match('/^[\w\-\.]+@([\w-]+\.)+[\w\-]{2,4}$/', $email) == 0) {
        array_push($err, "<p>Email must be in valid format</p>");
    }
    if (preg_match('/^[a-zA-Z]+$/', $pname) == 0) {
        array_push($err, "<p>Profile name cannot be empty and contain only letters</p>");
    }
    if (preg_match('/^[\w]+$/', $p1) == 0) {
        array_push($err, "<p>Password cannot be empty and contain only letter and number</p>");
    }
    else if (empty($p2)) {
        array_push($err, "<p>Please retype your password</p>");
    }
    if (strcmp($p1, $p2) !== 0) {
        array_push($err, "<p>Passwords do not match</p>");
    }

    return $err;
}

function lValidate($email, $pass) {
    $err = array();
    if (empty($email)) {
        array_push($err, "<p>Email cannot be empty</p>");
    }
    if (preg_match('/^[\w\-\.]+@([\w-]+\.)+[\w\-]{2,4}$/', $email) == 0) {
        array_push($err, "<p>Email must be in valid format</p>");
    }
    if (preg_match('/^[\w]+$/', $pass) == 0) {
        array_push($err, "<p>Password cannot be empty and contain only letter and number</p>");
    }
    return $err;
}
?>