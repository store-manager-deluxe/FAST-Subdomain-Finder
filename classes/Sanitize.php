<?php

namespace classes;

class Sanitize {

    public static function sanitizeUrl($domain) {
        return str_replace([
            'http://', 
            'https://',
            'www.'
            
        ], '', $domain);
    }
}