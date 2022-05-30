<?php
$pdo = new PDO('mysql:host=localhost;dbname=Marvel_test', 'root', '123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);