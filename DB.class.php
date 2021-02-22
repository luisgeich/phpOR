<?php

interface BD{

    /**
     * Funчуo de conexуo
     */
    public function conectar();


    /**
     * @param String    $query          Query a ser executada
     * @param boolean   $debug          Deve exibir a query e as mensagens de erro:
     */
    public function consultar($query, $debug = false);


    /**
     * @param String     query          Query a ser executada
     * @param boolean   $returnID       Deve retornar o ID da inserчуo?
     * @param boolean   $debug          Deve exibir a query e as mensagens de erro:
     * 
     */
    public function executar($query, $returnID = false , $debug = false);

}