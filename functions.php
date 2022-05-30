<?php
// making random string for image names
function randomString($n)
{
    $characters = '0123456789qwertyuiopasdfghjklzxcvbnm';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

include 'database.php';

function dataSearch($charName)
{
Global $pdo;

$statement = $pdo->prepare('SELECT * FROM characters WHERE Name like :keyword ORDER BY id DESC');
$statement->bindValue(":keyword", "%$charName%");  
$statement-> execute();
return $statement->fetchAll(PDO::FETCH_ASSOC);
}
