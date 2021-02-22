<?php
require_once("User.class.php");

// creates and save a new user

$user = new User();
$user
    ->setItem("name", "Luis")
    ->setItem("age", 21)
    ->setItem("country", "Brasil");

$user->save();


// updates user 1 age
$user2 = new User($user->id);
$user2->setItem("age", 22);
$user2->save();



