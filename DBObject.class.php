<?php
require_once("MySQL.class.php");
require_once("Utils.class.php");
require_once("StdObject.class.php");

class DBObject extends StdObject{
    
    /* 
    ** $init pode ser:
    ** int para buscar o a informção no banco de dados
    ** array para criar o objeto com os dados passa
    */
    
    public function __construct($init = 0, $condicaoExistencia = null) {
        
        $db = new MySQL();

        $this->table            = ! isset($this->table) ? strtolower(get_class($this)) : $this->table;
        $this->primary          = ! isset($this->primary) ? "id" : $this->primary;
        $this->autoIncrement    = ! isset($this->autoIncrement) ? 1 : $this->autoIncrement;
        
        if($init === 0){ // Deseja um objeto novo
            $this->setPrimaryValue(0);
        }
        
        else{
        
            if(! is_array($init) ){ // Não é array
                $id         = (int)$init;
                $WHERE      = $condicaoExistencia != null ? "AND $condicaoExistencia" : "";
                $consulta   = $db->consultar("SELECT * FROM $this->table WHERE $this->primary = $id $WHERE");

                if(@mysqli_num_rows($consulta) == 0){
                    throw new NotFoundException(get_class($this), $init); // Cria uma execessão para o programa saber qual foi o erro
                }

                else{
                    $dados = @mysqli_fetch_assoc($consulta); // Faz a consulta normal
                } 
            }
            
            else{ $dados = $init; }
            
            if(is_array($dados)){
                foreach($dados as $key => $value){
                    $this->$key = $value; // Tranforma os dados do banco em atributos do array
                }
            }
        }
    }
    
    /*
    ** Salva todos os doados do array que estão no banco de dados
    */
    
    public function save($soExibe = 0, $log = ""){
        
        // 1. Busca as colunas do banco
        $db = new MySQL();
        
        $values   = "";
        $consulta = $db->consultar("SHOW COLUMNS FROM $this->table");
        while($colunas = @mysqli_fetch_assoc($consulta)){
            
            $campo = $colunas["Field"];
            
            // 2. Se o atributo foi alterado e se não é ID, coloca no array para salvar
            if($campo != "id" && in_array($campo, $this->getAtributosAlterados())){
                if(isset($this->$campo)){
                    
                    if($this->$campo === "NULL") $values .= "$campo = NULL,"; // Precisa passar a String NULL para ele entender
                    else $values .= "$campo = '".Utils::clean_quotation_marks($this->$campo)."',"; // Usamos só o aspasSimples aqui pois ele é básico.
                }
            }
        }
        
        // 4. Verifica se é INSERT ou UPDATE
        
        $values     = substr($values, 0, -1);
        $acao       = $this->getPrimaryValue() ? "UPDATE" : "INSERT INTO";
        $WHERE      = $this->getPrimaryValue() ? "WHERE $this->primary = ".$this->getPrimaryValue() : "";
        $returnID   = $this->getPrimaryValue() ? 0 : 1;
            
        $query      = "$acao $this->table SET $values $WHERE";
        
        if($soExibe){
            echo "<pre>$query</pre>";
            return 1;
        }
        
        // 6. Executa e salva log
        
        $retorno = $db->executar($query, $returnID);
        
        $this->limparAtributosAlterados();
        
        // 7. Altera o ID se foi INSERT e retorna;
        
        if(! $this->getPrimaryValue() && $this->autoIncrement ){ $this->setPrimaryValue($retorno); }
        
        return $retorno != 0;
        
    }   
    
    public function delete($log = ""){
        $db = new MySQL();
        return $db->executar("DELETE FROM $this->table WHERE $this->primary = ".$this->getPrimaryValue());
    }

    // Retorna qual é a chave primária, ex: id, código, matricula, cpf e etc.
    private function getPrimaryKey(){
        return $this->primary;
    }

    // Retorna o valor da chave primária, ex: 12, "ABCD", 024563, 030.863.340-97 e etc.
    public function getPrimaryValue(){
        $primary = $this->getPrimaryKey();
        return $this->$primary;
    }

    // Altera o valor da chave primária, independente de quem ela seja;
    public function setPrimaryValue($value){
        $primary = $this->getPrimaryKey();
        $this->$primary = $value;
        return $this; 

    }
    
}




?>