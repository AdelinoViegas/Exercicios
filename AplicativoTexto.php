<?php 


if(isset($_POST['dados'])){
 $new_text = '';
 $number_letters = 0;
 $text = $_POST['text'];	  
 $ocurrenceActual = 0;	   	    
 $letter_frequency = '';
 $letters = 'abcdefghijklmnopqrstuvwxyzãáàçéêíõóú';

 for($i=0; $i < strlen($text); $i++){
   if($text[$i] !== " "){
   	 $new_text .= $text[$i]; 
   	 $number_letters++; 
   }
 }

 $letter_first = $new_text[0];
 $letter_last =  $new_text[strlen($new_text)-1];



for($i=0; $i < strlen($letters); $i++){
     
   if(substr_count($text,strtoupper($letters[$i])) > 0){
     $ocurrence = substr_count($text,strtoupper($letters[$i]));
   }else{
   	 $ocurrence = substr_count($text,$letters[$i]);
   }  
   

    
   if($ocurrence >= $ocurrenceActual){
      if($ocurrence > $ocurrenceActual){
        $ocurrenceActual = $ocurrence;
      	$letter_frequency = $letters[$i]; 
      }else{
      	$ocurrenceActual = $ocurrence;
      	$letter_frequency .= $letters[$i]; 
      }
   } 

}



}





 ?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="utf-8">
	<title>Aplicativo-texto</title>
	<style>

		body{
			font-family: sans-serif;
			background-color: rgb(220,220,220);
		}

		textarea{
			padding:15px;
			width:400px;
			height:200px;
			font: normal 20px sans-serif;
		}

		input{
			margin-top:20px;
			display: block;
			font:normal 20px sans-serif;
			padding:10px 14px;
		}

		p{
			font-size:30px;
		}

		span{
			font-weight:bold;
		}

	</style>
</head>
<body>

<form action="<?= $_SERVER['PHP_SELF']?>" method="post"> 
 <textarea name="text"></textarea>
 <input type="submit" name="dados" value="Obter dados"/>
</form>


<p>
	Letra mais frequente:
	<span> 
	  <?php if(isset($number_letters)){ 
	  	echo $letter_frequency;
	  }else{
        echo "0";
	  } 

	  ?>
	</span>	
</p>

<p>
	Número de letras:
	<span> 
	  <?php if(isset($number_letters)){ 
	  	echo $number_letters;
	  }else{
        echo "0";
	  } 

	  ?>
	</span>	
</p>

<p> Primeira letra:
	<span> 
     <?php if(isset($letter_first)){ 
	  	echo $letter_first;
	  }else{
        echo "0";
	  } 
	?>	
    </span>
</p>

<p> Segunda letra: 
	<span>
     <?php if(isset($letter_last)){ 
	  	echo $letter_last;
	  }else{
        echo "0";
	  } 
	?> 
    </span>
</p>

</body>
</html>
