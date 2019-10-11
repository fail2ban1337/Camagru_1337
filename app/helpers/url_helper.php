<?php
// simple page redirect
function redirect($page)
{
    header('location: ' . URLROOT . '/' . $page);
}

function createUserSession($user)
{
    $_SESSION['logout'] = md5(uniqid($user->name, true));
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
}

function DestroyUserSession()
{
    unset($_SESSION['logout']);
    unset($_SESSION['user_id']);
    ($_SESSION['user_email']);
    ($_SESSION['user_name']);
}

function isLoggedIn()
{
    if (isset($_SESSION['user_id']))
    {
        return true;
    }else {
        return false;
    }
}