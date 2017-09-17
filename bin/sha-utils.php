<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/17/2017
 * Time: 1:17 PM
 */

    $text = $argv[0];

    $value = hash("sha512", "admin");

    echo $value;