<?php

require_once("DB.class.php");
require_once("Utils.class.php");


class MySQL implements BD {

    public function conectar(){

        $host       = "localhost";  //TODO: substitua com seus dados
        $username   = "username";   //TODO: substitua com seus dados
        $password   = "password";   //TODO: substitua com seus dados
        $database   = "database";   //TODO: substitua com seus dados
        
        $conex = mysqli_connect($host, $username, $password, $database);
        if (mysqli_connect_errno()){
            throw new Exception("Not able to conect to database");
        }

        else {
            mysqli_set_charset($conex, "latin1");
            return $conex;
        }
    }

    public function consultar($query, $debug = false){
        $conex      = $this->conectar();
        $results    = mysqli_query($conex, $query);

        if($debug){
            echo $query."<br>";
            if($error = mysqli_error($results)){
                echo "ERROR: $error<br>";
            }
        }

        mysqli_close($conex);
	    return $results;

    }

    public function executar($query, $returnID = false, $debug = false){
        $conex   = $this->conectar();
	    $results = mysqli_query($conex, $query);  

        if($debug){
            echo $query."<br>";
            if($error = mysqli_error($results)){
                echo "ERROR: $error<br>";
            }
        }

        if($returnID){
            return mysqli_insert_id($conex);
        }

        else return $results;
    }

}

?>