<?php

namespace classes;


class Display {

    public static function menu() {
    
        system("clear");
        $time = date('[H:i:s]');
        $user = get_current_user();
        $banner = [
            "________________________________________________",
            "================================================",
            "      Store Manager Fast Subdomain Finder       ",
            "================================================",
            " Usage        : core.php [URL] ",
            " Time         : {$time}",
            " User         : {$user}",
            "_______________________________",
            ""
            
        ];

        foreach($banner as $output) 
        {
            foreach(str_split($output) as $characters) 
            {
                echo $characters;
                usleep(1200);
            }

            echo "\n";
        }
        
    }
}