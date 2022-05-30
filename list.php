<?php
include 'database.php';
$statement = $pdo->prepare('SELECT * FROM characters ORDER BY id DESC');
$statement-> execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Document</title>
</head>
<body class="bg-black text-white">
  <div class="flex content-center"> 
    <a href="index.php" class="block bg-marvelRed px-5 py-2 my-10 rounded-lg mx-auto">find more characters</a>
  </div>
  <tbody>
      <?php foreach ($products as $i => $character): ?>
        <section class='px-4 my-8 max-w-3xl mx-auto space-y-6'>
            <div class="flex content-center">
              <img src=<?=$character['Image']?> alt="photo of product" class="product-img">
            </div>
            <div>
              <p>Name: <?=$character['Name'] ?></p>
              <p>Description: <?=$character['Description'] ?></p>
              <p>Number of comics character is involved in: <?=$character['Comics']?></p>
              <p>Number of series character is involved in: <?=$character['Series']?></p>
            </div>
            <br>
            <br>
        </section>
      <?php endforeach ?>
  </tbody>
</table>
</body>
</html>