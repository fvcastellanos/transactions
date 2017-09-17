<?php

namespace AppBundle\Service;


class ShaUtils
{
    public static function sha512($text) {
        return hash("sha512", $text);
    }
}