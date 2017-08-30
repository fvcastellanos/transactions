<?php

    require_once 'bootstrap.php';

    
    $m = new Mustache_Engine;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>
    <?php 
        echo $m->render('Hello, {{planet}}!', array('planet' => 'World'));
    ?>
    </p>
</body>
</html>