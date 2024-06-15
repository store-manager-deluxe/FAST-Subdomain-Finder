<?php

namespace Classes;

class Display {

    public static function getCpuInfo() {
        $cpuInfo = file_get_contents('/proc/cpuinfo');
        preg_match('/model name\s+:\s+(.+)/', $cpuInfo, $matches);
        return $matches[1] ?? 'Unknown CPU';
    }

    public static function getMemoryInfo() {
        $memInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+) kB/', $memInfo, $matches);
        return round($matches[1] / 1024 / 1024, 2) . ' GB' ?? 'Unknown Memory';
    }

    public static function getDiskUsage() {
        $diskTotal = disk_total_space("/");
        $diskFree = disk_free_space("/");
        $diskUsed = $diskTotal - $diskFree;
        $diskUsage = round($diskUsed / $diskTotal * 100, 2);
        return $diskUsage . '% used';
    }

    public static function menu() {
    
        system("clear");
        $time = date('[H:i:s]');
        $user = get_current_user();
        $hostname = gethostname();
        $os = php_uname('s') . " " . php_uname('r');
        $ip = gethostbyname($hostname);
        $cpu = self::getCpuInfo();
        $memory = self::getMemoryInfo();
        $disk = self::getDiskUsage();

        $banner = [
            " \033[1;36m________________________________________________________________________________\033[0m",
            " \033[1;36m================================================================================\033[0m",
            " \033[1;32m  ____  _                   __  __                                     \033[0m",
            " \033[1;32m / ___|| |_ ___  _ __ ___  |  \/  | __ _ _ __   __ _  __ _  ___ _ __   \033[0m",
            " \033[1;32m \___ \| __/ _ \| '__/ _ \ | |\/| |/ _` | '_ \ / _` |/ _` |/ _ \ '__|  \033[0m",
            " \033[1;32m  ___) | || (_) | | |  __/ | |  | | (_| | | | | (_| | (_| |  __/ |     \033[0m",
            " \033[1;32m |____/ \__\___/|_|  \___| |_|  |_|\__,_|_| |_|\__,_|\__, |\___|_|     \033[0m",
            " \033[1;32m                                                     |___/              \033[0m",
            " \033[1;36m================================================================================\033[0m",
            " \033[1;36m                         TORIFIED SUBDOMAIN ENUMERATION                         \033[0m",
            " \033[1;36m================================================================================\033[0m",
            " \033[1;37m Usage        : core.php [URL]                                                   \033[0m",
            " \033[1;37m Time         : {$time}                                                          \033[0m",
            " \033[1;37m User         : {$user}                                                          \033[0m",
            " \033[1;37m Hostname     : {$hostname}                                                      \033[0m",
            " \033[1;37m Operating Sys: {$os}                                                            \033[0m",
            " \033[1;37m IP Address   : {$ip}                                                            \033[0m",
            " \033[1;37m CPU          : {$cpu}                                                           \033[0m",
            " \033[1;37m Memory       : {$memory}                                                        \033[0m",
            " \033[1;37m Disk Usage   : {$disk}                                                          \033[0m",
            " \033[1;36m________________________________________________________________________________\033[0m",
            ""
        ];

        foreach($banner as $output) 
        {
            foreach(str_split($output) as $characters) 
            {
                echo $characters;
                usleep(5000);
            }
            echo "\n";
        }  
        sleep(3);
    }
}
