<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    include "name-search.php";
    include "functions.php";

    $random = randomString(8);
    $error = '';

    if(empty($_POST['character'])){
        $error = 'Please enter character name';
    }elseif(!$arrayResults = $decoded["data"]["results"][0] ?? null) {
        $error = "Wrong character name or we don't have information about that one ;(";
    }else{
        // order info fetched from marvel api
        $charName = $arrayResults["name"];
        $description = $arrayResults["description"];
        if(empty($description)){
            $description = "No description provided";
        }
        $image = $arrayResults["thumbnail"]["path"] . '.' . $arrayResults["thumbnail"]["extension"];
        $numComics = $arrayResults["comics"]["available"];
        $numSeries = $arrayResults["series"]["available"];
        // save image file in folder
        $content = file_get_contents($image);
        if (!is_dir('images')) {
            mkdir('images');
        }
        if (!is_dir('images/'.$random)){
            mkdir('images/'.$random);
        }
        $imageCharName = str_replace(' ', '_', $charName);
        $imagePath = 'images/'.$random.'/'.$imageCharName.'.'.$arrayResults['thumbnail']['extension'];
        file_put_contents($imagePath, $content);
        

        
        include 'database.php';

        // fetch info from database
        $products = dataSearch($charName);


        if(!$products ?? null){
        // send information in database if it doesn't already exists
        $statement = $pdo->prepare("INSERT INTO characters (Name, Description, Comics, Series, Image)
        VALUES (:Name, :Description, :Comics, :Series, :Image)");        
        $statement->bindValue(':Name', $charName);
        $statement->bindValue(':Description', $description);
        $statement->bindValue(':Comics', $numComics);
        $statement->bindValue(':Series', $numSeries);
        $statement->bindValue(':Image', $imagePath);
        $statement->execute();
        }

        // Search again if it just been added
        $product = dataSearch($charName);
        
        // order info from database
        $charInfo = $product[0];
        $character = $charInfo['Name'];
        $characterDescription = $charInfo['Description'];
        $comics = $charInfo['Comics'];
        $series = $charInfo['Series'];
        $charImage = $charInfo['Image'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Marvel Character Search</title>

</head>
<body class="bg-black text-white">
    <!-- Input Section -->
    <section class="px-4 my-8 max-w-3xl mx-auto space-y-6">
        <div class="flex content-center">
            <h1 class="text-3xl mx-auto">Find your favourite Marvel character ;)</h1>
        </div>
        <form action="" method="POST" class="flex flex-col content-center">
            <input class="border text-black border-gray-400 block py-2 px-4 w-full rounded focus:outline-none focus:border-marvelOrange" type="text" name="character" placeholder="Iron Man...">
            <button type="submit" class="block bg-marvelRed px-5 py-2 my-10 rounded-lg" >Submit</button>
        </form>
    </section>
<?php if(!empty($error)): ?>
    <div class="flex content-center">
        <h1 class="text-3xl mx-auto"><?=$error?></h1>
    </div>
        <?php elseif($_POST['character'] ?? null && empty($error)): ?>
        <section class='px-4 my-8 max-w-3xl mx-auto space-y-6'>
            <div class="flex content-center">
                <img src="<?= $charImage ?>" alt="photo of character" class="block">
            </div>
            <div class="">
                <p class="py-2">Name: <?=$character?></p>
                <p class="py-2">Description: <?=$characterDescription?></p>
                <p class="py-2">Number of comics character is involved in: <?=$comics?></p>
                <p class="py-2">Number of series character is involved in: <?=$series?></p>
            </div>
        </section>
        
        <?php endif ?>
        <div class="flex content-center">
        <a href="list.php" class="bg-marvelRed px-5 py-2 my-10 rounded-lg block mx-auto" target="_blank" >See All Submitted characters</a>
        </div>
</body>
</html>