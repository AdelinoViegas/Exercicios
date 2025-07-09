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
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #6885f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: sans-serif;
    }

    /* Calculator Form */
    .calculator {
      background-color: #03124e;
      padding: 30px;
      border-radius: 8px;
      color: #fff;
      width: 100%;
      max-width: 400px;
    }

    .calculator__title {
      text-align: center;
      margin-bottom: 20px;
    }

    .calculator__field {
      margin-bottom: 20px;
    }

    .calculator__label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .calculator__input {
      width: 100%;
      height: 45px;
      padding: 8px;
      font-size: 20px;
      background-color: #0625a2;
      color: #fff;
      border: 1px solid #0050ff;
      border-radius: 4px;
      outline: none;
    }

    /* Submit Button */
    .calculator__submit {
      width: 100%;
      height: 50px;
      font-size: 22px;
      font-weight: bold;
      background-color: #fff;
      color: #03124e;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .calculator__submit:hover {
      background-color: #ddd;
    }

  </style>
</head>
<body>
  <form action="" method="post" class="calculator">
    <h2 class="calculator__title">Calculadora</h2>

    <div class="calculator__field">
      <label class="calculator__label">Digite uma expressão válida:</label>
      <input type="text" name="expression" class="calculator__input" required
        value="<?= $calculator->getValidatedExpression() ?? '' ?>">
    </div>

    <div class="calculator__field">
      <label class="calculator__label">Resultado:</label>
      <input type="text" name="result" class="calculator__input"
        value="<?= $calculator->getResult() ?? '' ?>" readonly>
    </div>

    <div>
      <input type="submit" name="btn-expression" value="Calcular" class="calculator__submit">
    </div>
  </form>
</body>
</html>