<?php

namespace classes;

class Logger {

    public static $colors = [
        'reset' => "\033[0m",
        'black' => "\033[0;30m",
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[0;33m",
        'blue' => "\033[0;34m",
        'magenta' => "\033[0;35m",
        'cyan' => "\033[0;36m",
        'white' => "\033[0;37m",
        'bold_black' => "\033[1;30m",
        'bold_red' => "\033[1;31m",
        'bold_green' => "\033[1;32m",
        'bold_yellow' => "\033[1;33m",
        'bold_blue' => "\033[1;34m",
        'bold_magenta' => "\033[1;35m",
        'bold_cyan' => "\033[1;36m",
        'bold_white' => "\033[1;37m",
        'underline_black' => "\033[4;30m",
        'underline_red' => "\033[4;31m",
        'underline_green' => "\033[4;32m",
        'underline_yellow' => "\033[4;33m",
        'underline_blue' => "\033[4;34m",
        'underline_magenta' => "\033[4;35m",
        'underline_cyan' => "\033[4;36m",
        'underline_white' => "\033[4;37m",
    ];

    private static function futuristicText($text, $color)
    {
        return $color . $text . self::$colors['reset'];
    }

    public static function log($message)
    {
        $time = date('[H:i:s]');
        echo self::$colors['bold_cyan'] . $time . self::$colors['reset'] . ": " . self::futuristicText($message, self::$colors['bold_green']) . "\n";
    }

    public static function errorLog($message)
    {
        $time = date('[H:i:s]');
        echo self::$colors['bold_cyan'] . $time . self::$colors['reset'] . ": " . self::futuristicText($message, self::$colors['bold_red']) . "\n";
    }
}
