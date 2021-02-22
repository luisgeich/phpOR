<?php
require_once("DBObject.class.php");

class User extends DBObject{

    public $table           = "users";
    public $primary         = "id";
    public $autoIncrement   = true;

}

?>