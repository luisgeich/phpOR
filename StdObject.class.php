<?php

/*
** Essa classe cont�m m�todos uteis a objetos
*/

class StdObject{
    
    private $atributosAlterados = array();

    /*
    ** Pega os dados vindos do array $init e transforma em atributos
    */
    
    function __construct($init = array()) {
        foreach($init as $key => $value){
            $this->$key = $value;
        }
    }
    
    
    /**
    ** Atualiza qualquer atributo
    ** Retorna a propria classe para podermos encadear
    ** @param mixed $key    O nome da coluna no BD [caso for atualizar]
    ** @param mixed $value  O valor do atributo
    ** @return StdObject    O pr�prio objeto
    */
    
    public function setItem($key, $value){
        $this->$key = $value;
        $this->atributosAlterados[] = $key;
        
        return $this;
    }
    
    
    /*
    ** Retorna os atributos alterados n�o repetidos
    */
    
    public function getAtributosAlterados(){
        return array_unique($this->atributosAlterados);
    }
    
    
    /*
    ** Zera a lista de atributos alterados
    */
    
    public function limparAtributosAlterados(){
        $this->atributosAlterados = [];
    }
    

    public function __toString(){
        return "";
    }

    public function toArray($options = []){
        $saida = [];

        foreach(get_object_vars($this) as $key => $value){

            if($key == "atributosAlterados") continue;
            if(in_array("UPPER_CASE", $options)) $key = strtoupper($key);

            if(is_object($value)){
                $saida[$key] = method_exists($value, "toArray") ? $value->toArray() : "";
            }

            else{
                $saida[$key] = $key == "senha" ? "************" : $value;
            }
        }

        return $saida;
    }
}

/**
* Usu�rio informou um valor inv�lido
**/
class InvalidValueException extends Exception{
    /**
    * @param string $mensagem   explica��o do erro cometido ou o pr�prio valor inv�lido
    **/
    public function __construct($mensagem){
        parent::__construct("Valor inv�lido: ".$mensagem);
    }
}

/**
* N�o encontrou um dado necess�rio � execu��o
**/
class NotFoundException extends Exception { 
    /**
    * @param string $dado   Nome do dado que n�o foi encontrado
    * @param mixed $init    Valor (id ou array) usado para buscar o $dado 
    **/
    public function __construct($dado, $init){
        parent::__construct("Dado n�o encontrado: ".$dado);
    }
}

/**
* N�o salvou o objeto ou valor 
**/
class SaveException extends Exception{

    /**
    * @param mixed $objeto      Objeto (ou outro tipo de valor) que esta sendo salvo
    * @param string $detalhes   Opcional. Detalhes do que estava sendo salvo ou onde
    **/
    public function __construct($objeto, $detalhes = ""){
        
        if(is_object($objeto)){
            $classe     = get_class($objeto);
            $mensagem   = "N�o salvou $classe: ".Utils::arrayToJSON($objeto->toArray()).($detalhes ? " ao $detalhes" : "");
        }

        else{ $mensagem   = "N�o salvou $detalhes ".(is_array($objeto) ? Utils::arrayToJSON($objeto) : $objeto); }

        parent::__construct($mensagem);
    }
}

/**
* N�o possui permiss�o para acessar
**/
class PermissionException extends Exception{
    
}