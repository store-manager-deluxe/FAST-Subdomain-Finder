<?php

namespace classes;

class Filex
{

    public function genFilename($prefix = 'valid_', $ext = '.txt')
    {
        do {
            $rand = bin2hex(random_bytes(8));
            $fileName = $prefix . $rand . $ext;
        } while (file_exists($fileName));

        return $fileName;
    }
}