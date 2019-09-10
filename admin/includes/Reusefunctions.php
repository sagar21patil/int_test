<?php
class resuseFunctions{
    
     public $_request = array();
     public $_error='';
     
    public function __construct(){
            $this->inputs();
    }

/*Function to set JSON output*/
public function output($Return=array()){
    /*Set response header*/
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    /*Final JSON response*/
    exit(json_encode($Return));
}
    
public function cleanInputs($data){
       $clean_input = array();
       if(is_array($data)){
               foreach($data as $k => $v){
                       $clean_input[$k] = $this->cleanInputs($v);
               }
       }else{
               if(get_magic_quotes_gpc()){
                       $data = trim(stripslashes($data));
               }
               $data = strip_tags($data);
               $clean_input = trim($data);
       }
       return $clean_input;
}
public function get_request_method(){
       return $_SERVER['REQUEST_METHOD'];
}

public function inputs(){
   switch($this->get_request_method()){

           case "POST":
                   // if data get in json format
                   $json=json_decode(file_get_contents('php://input'), true);
                   // End
                   if($json==null)
                       $json = $_POST;

                   $this->_request = $this->cleanInputs($json);
                   break;
           case "GET":
           case "DELETE":
                   $this->_request = $this->cleanInputs($_GET);
                   break;
           case "PUT":
                   parse_str(file_get_contents("php://input"),$this->_request);
                   $this->_request = $this->cleanInputs($this->_request);
                   break;
           default:
                  $this->_request ="";
                   break;
   }
}		

}

?>
