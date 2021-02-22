<?php 

class Utils{

    public static function arrayToJSON($array, $scape = 1, $option = JSON_PRESERVE_ZERO_FRACTION){
        if($array == null) return "[]";

        $json = json_encode(Utils::utf8_encode_array($array), $option );
        $json = str_replace("\\r", " ", $json);
        $json = str_replace("\\n", " ", $json);
        $json = str_replace("\\t", " ", $json);
    
        if($scape){
            $json = str_replace("'", "\'", $json);
        }
        
        return $json;
    }

    public static function utf8_encode_array(&$array){
	
        if(!function_exists("encode_items")){
            function encode_items(&$item, $key){
                $item = str_replace('"', "'", $item);
                $item = utf8_encode($item);
                
            }
        }
        
        array_walk_recursive($array, 'encode_items');
    
        return $array;
    }
    
    public static function clean_quotation_marks($haystack){
        
        $changeThis = array("<?", "?>", "\'", "\`", "\?", "'", "?", "?", "%u2018", "%u2019", "%u201C", "%u201D", "%u2013", "\\\\");
        $forThis    = array("&lt;?", "?&gt;", "&rsquo;", "&lsquo;", "&rsquo;", "&rsquo;", "&lsquo;", "&rsquo;", "&lsquo;", "&rsquo;", "&lsquo;", "&rsquo;", "-", "\\");
       
        return str_replace($changeThis, $forThis, $haystack);
    }

}

?>