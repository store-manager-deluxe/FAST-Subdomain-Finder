<?php

namespace Classes\Masking;



class Headers{

    public function randomAgent(callable $collect = null) {

        if($collect === null) {
            $userAgents = [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Firefox/89.0',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0'
            ];

            return $userAgents[array_rand($userAgents)];
        } else {

            $userAgents = $collect();
            if(!is_array($userAgents) || empty($userAgents)) {

                return $this->randomAgent(null);
            }

            return $userAgents[array_rand($userAgents)];
        }
        

    }
}