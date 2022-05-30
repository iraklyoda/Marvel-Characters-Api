<?php
    $curl = curl_init("");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $name = strtolower($_POST['character']) ?? null;
    
    // Building access keys and bash
    $ts = time();
    $public_key = "0d4408a0489f11e999edd0428a4ae039";
    $private_key = "9a58ae484c6a09d91bd16e90d8a08b99b623d03e";
    $hash = md5($ts . $private_key. $public_key);
    
    $query = array(
        "name" => $name,
        "orderBy" => "name",
        "limit" => "20",
        "apikey" => $public_key,
        "ts" => $ts,
        'hash' => $hash,
    );
    $marvel_url = "https://gateway.marvel.com:443/v1/public/characters?" . http_build_query($query);
    
    curl_setopt($curl, CURLOPT_URL, $marvel_url);
    $responseRepo = curl_exec($curl);
    curl_close($curl);
    $decoded = json_decode($responseRepo, true);

    
    if(empty($decoded['data']['results'])){
        return;
    }

    $arrayResults = $decoded["data"]["results"][0];
