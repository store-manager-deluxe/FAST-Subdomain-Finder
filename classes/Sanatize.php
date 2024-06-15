<?php

namespace classes;

class Sanatize {

    public static function sanatizeUrl($domain) {
        return str_replace(['http://', 'https://'], '', $domain);
    }
}