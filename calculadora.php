<?php 
  class Calculator {
      private string $expression;
      private string $filteredExpression = '';
      private string $validatedExpression = '';
      private array $invalidChars = '=!@#$%¨&*()_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwyz?<>~çÇ:;,{}[]`´';
      private bool $isValid = true;
      private array $operators = [];
      private array $numbers = [];
      private float|string $result = 0;

      public function __construct(string $expression) {
          $this->expression = $expression;
      }

      public function getResult(): float|string {
          return $this->result;
      }

      public function getValidatedExpression(): string {
          return $this->validatedExpression;
      }

      public function filter(): void {
          $this->filteredExpression = str_replace(' ', '', $this->expression);
          if ($this->filteredExpression === '') {
              $this->filteredExpression = 'Null';
          }
      }

      public function validate(): void {
          for ($i = 0; $i < strlen($this->filteredExpression); $i++) {
              $char = $this->filteredExpression[$i];
              if (str_contains($this->invalidChars, $char)) {
                  $this->isValid = false;
                  $this->result = "Expressão inválida";
                  return;
              }
              $this->validatedExpression .= $char;
          }
      }

      public function calculate(): void {
          if (!$this->isValid) return;

          $expression = str_replace(['+', '-', 'x', '/'], ' ', $this->validatedExpression);
          $this->numbers = array_map('floatval', explode(' ', $expression));

          preg_match_all('/[\+\-x\/]/', $this->validatedExpression, $matches);
          $this->operators = $matches[0];

          if (empty($this->numbers) || empty($this->operators)) {
              $this->result = "Expressão incompleta";
              return;
          }

          $this->result = $this->numbers[0];
          for ($i = 0; $i < count($this->operators); $i++) {
              $next = $this->numbers[$i + 1] ?? 0;
              switch ($this->operators[$i]) {
                  case '+': $this->result += $next; break;
                  case '-': $this->result -= $next; break;
                  case 'x': $this->result *= $next; break;
                  case '/':
                      $this->result = $next == 0 ? "Erro de divisão" : $this->result / $next;
                      break;
              }
              if (!is_numeric($this->result)) break;
          }
      }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $calculator = new Calculator($_POST['expression']);
      $calculator->filter();
      $calculator->validate();
      $calculator->calculate();
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
