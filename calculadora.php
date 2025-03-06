<?php 
class Calculator{

	private $expression; 
	private $filter_expression;
	private $validate_expression;
	private $character_invalid = [];
	private $valid_math_expression;
  private $operator_array;
  private $array_number;
  private $result;
  private $text_expression;

	public function __construct($expression){
		$this->expression = $expression;
    }

	public function getResult(){
		return $this->result;
	}

	public function getValidate_Expression(){
		return $this->validate_expression;
	}

	public function filter(){
			 /*Eliminar os espaços da string*/   
		   for($i=0; $i<strlen($this->expression); $i++){
		        if($this->expression[$i] !== ' '){
		         $this->filter_expression .= $this->expression[$i];
		        }
		   }

		   if($this->filter_expression === null){
		   	   $this->filter_expression = 'Null';
		   } 	  	  
	}

	public function Validate(){
		$this->character_invalid = "=!@#$%¨&*()_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwyz?<>~çÇ:;,{}[]`´";

    for($i=0; $i < strlen($this->filter_expression); $i++){
       if( str_contains($this->character_invalid,$this->filter_expression[$i])){
           $this->valid_math_expression = false;
      	   $this->result = "Expressão inválida";
      	   break;
      }else{
         $this->validate_expression .= $this->filter_expression[$i];    
         $this->valid_math_expression = true;
      }
     } 
	}

public function Calculate(){
   $this->text_expression = $this->validate_expression;
   if($this->valid_math_expression === true){
 
  for($i=0; $i<strlen($this->text_expression); $i++){    
      if($this->text_expression[$i] === '+'){
        $this->operator_array[] = $this->text_expression[$i];
      }elseif($this->text_expression[$i] === '-'){
        $this->operator_array[] = $this->text_expression[$i];
      }elseif($this->text_expression[$i] === 'x'){        
        $this->operator_array[] = $this->text_expression[$i];
      }elseif($this->text_expression[$i] === '/'){
        $this->operator_array[] = $this->text_expression[$i];
      }
    } 


  for($i=0; $i<strlen($this->text_expression); $i++){   

      if($this->text_expression[$i] === '+'){
         $this->text_expression = str_replace('+',' ',$this->text_expression);
      }

      if($this->text_expression[$i] === '-'){
         $this->text_expression = str_replace('-',' ',$this->text_expression);
      }

      if($this->text_expression[$i] === 'x'){
        $this->text_expression = str_replace('x',' ',$this->text_expression);
      }

      if($this->text_expression[$i] === '/'){
         $this->text_expression = str_replace('/',' ',$this->text_expression);
       }   
    }
     
   $this->array_number = explode(' ',$this->text_expression);

   for($i=0; $i < count($this->array_number); $i++){
       $this->array_number[$i] = (float)$this->array_number[$i];         
   }


  if(isset($this->operator_array) && !($this->array_number[0] === '') ){
    for($i=0; $i<count($this->operator_array); $i++){
      if($this->operator_array[$i] === '+'){
           $this->result = $this->array_number[$i] + $this->array_number[$i+1];
           $this->array_number[$i+1] = $this->result;
      }

      if($this->operator_array[$i] === '/'){
            
          try{ 
	          $this->result = $this->array_number[$i] / $this->array_number[$i+1];
	          $this->array_number[$i+1] = $this->result;
          }catch(DivisionByZeroError $error){
             $this->result = "Erro de divisão";
             break;
          }
      }

      if($this->operator_array[$i] === '-'){

           $this->result = $this->array_number[$i] - $this->array_number[$i+1];
               $this->array_number[$i+1] = $this->result;
      }

      if($this->operator_array[$i] === 'x'){
           $this->result = $this->array_number[$i] * $this->array_number[$i+1];
               $this->array_number[$i+1] = $this->result;
      }  
   }
     $this->validate_expression = $this->result;

  }else{
    $this->result = $this->validate_expression;
  }
}

	}
}

if(isset($_POST['btn-expression'])){
   $calculo = new Calculator($_POST['expression']);	
   $calculo->filter();
   $calculo->Validate();
   $calculo->Calculate();
}

 ?>
	
<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>calculadora</title>
    <style>

    	*{
    		margin: 0;
    		padding:0;
    		box-sizing:border-box;
    	}
      
      body{
        background-color: rgb(104,133,249);
        display: flex;
        justify-content: center;
        align-items: center;
        height:100vh;
      }

      form{
         display: inline-block;
         background-color: rgb(3,18,78);
         padding: 30px;
         border-radius:5px;
         color:#fff;
       }

       label{
         font-family: sans-serif;
       }

      input[name='expression'],input[name='result']{
           width:250px;
           height:30px;
           padding: 4px;
           font-size:26px;
           background: rgb(6,37,162);
           color:#fff;
           outline:none;
           border:1px solid blue;
      }	

      input[type='submit']{
        border-radius:4px;
        width:100%;
        height:50px;
        font-size:24px;
        font-weight:bold;
        background-color: rgb(255,255,255);
        cursor:pointer;
        transition: .3s;
      }

      input[type='submit']:hover{
        background-color: rgb(230,230,230);      
      }

     input[type='text']{
        padding:10px 6px;
        height:50px;
        width: 350px;
        
      }

      p{
        display: flex;
        row-gap:10px;
        flex-direction:column;
        font-weight: bold;
        font-size:20px;
        margin: 20px 0px;
      }


      h2{
        font-family: sans-serif;
        text-align: center;
      }
   </style>
</head>
<body>

   <form action="" method="post">
        <h2>Calculadora</h2>
      <p>
        <label>Digite uma expressão válida:</label>
        <input type="text" name="expression" value="<?php
            	if(isset($calculo)){
                 echo $calculo->getValidate_Expression();
              }
        ?>"required> 
      </p>
      <p>
        <label>Resultado:</label>
        <input type="text" name="result" value="<?php
            if(isset($calculo)){   
               echo $calculo->getResult();
            }  
         ?>"> 
      </p>
      <P>
        <input type="submit" name="btn-expression" value="Calcular">
      </P>
   </form>
</body>
</html>
