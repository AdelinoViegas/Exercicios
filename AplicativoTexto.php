<?php 

	class TextAnalyzer
	{
			private string $rawText;
			private string $normalizedText = '';
			private int $letterCount = 0;
			private string $firstLetter = '';
			private string $lastLetter = '';
			private string $mostFrequentLetter = '';
			private int $maxFrequency = 0;
			private string $letters = 'abcdefghijklmnopqrstuvwxyzãáàçéêíõóú';

			public function __construct(string $text)
			{
					$this->rawText = trim($text);
					$this->analyze();
			}

			private function analyze(): void
			{
					$cleanText = str_replace(' ', '', $this->rawText);
					$this->normalizedText = $cleanText;
					$this->letterCount = strlen($cleanText);

					if ($this->letterCount > 0) {
							$this->firstLetter = $cleanText[0];
							$this->lastLetter = $cleanText[$this->letterCount - 1];
					}

					$textLower = mb_strtolower($this->rawText);

					foreach (mb_str_split($this->letters) as $letter) {
							$count = substr_count($textLower, $letter);

							if ($count > $this->maxFrequency) {
									$this->maxFrequency = $count;
									$this->mostFrequentLetter = $letter;
							} elseif ($count === $this->maxFrequency && $count > 0) {
									$this->mostFrequentLetter .= ", $letter";
							}
					}
			}

			public function getData(): array
			{
					return [
							'letterCount' => $this->letterCount,
							'firstLetter' => $this->firstLetter,
							'lastLetter' => $this->lastLetter,
							'mostFrequentLetter' => $this->mostFrequentLetter ?: 'Nenhuma',
					];
			}
	}

	$data = null;

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
			$analyzer = new TextAnalyzer($_POST['text']);
			$data = $analyzer->getData();
	}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="utf-8">
	<title>Aplicativo-texto</title>
	<style>
		body {
			background-color: #dcdcdc;
			font-family: sans-serif;
			padding: 2rem;
			display: flex;
			justify-content: center;
		}

		.text-app {
			background: #fff;
			padding: 2rem;
			border-radius: 6px;
			max-width: 600px;
			width: 100%;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
		}

		.text-app__textarea {
			width: 100%;
			height: 160px;
			padding: 1rem;
			font-size: 18px;
			resize: vertical;
			margin-bottom: 1rem;
		}

		.text-app__submit {
			display: block;
			padding: 0.75rem 1rem;
			font-size: 18px;
			cursor: pointer;
			background-color: #555;
			color: white;
			border: none;
			border-radius: 4px;
			transition: background 0.3s ease;
		}

		.text-app__submit:hover {
			background-color: #333;
		}

		.text-app__info {
			margin-top: 2rem;
			font-size: 22px;
		}

		.text-app__info span {
			font-weight: bold;
			margin-left: 8px;
			color: #0046ad;
		}
	</style>
</head>
<body>
  <form method="post" class="text-app">
    <textarea name="text" class="text-app__textarea" placeholder="Digite seu texto aqui..."><?= $_POST['text'] ?? '' ?></textarea>
    <input type="submit" name="dados" value="Obter dados" class="text-app__submit">

    <?php if ($data): ?>
      <div class="text-app__info">
        <p>Letra mais frequente:<span><?= $data['mostFrequentLetter'] ?></span></p>
        <p>Número de letras:<span><?= $data['letterCount'] ?></span></p>
        <p>Primeira letra:<span><?= $data['firstLetter'] ?></span></p>
        <p>Última letra:<span><?= $data['lastLetter'] ?></span></p>
      </div>
    <?php endif; ?>
  </form>
</body>
</html>
