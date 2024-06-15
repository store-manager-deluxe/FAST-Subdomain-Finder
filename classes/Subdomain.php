<?php

namespace classes;

use classes\Logger;
use GuzzleHttp\Client;
use classes\Filex;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Utils;

class Subdomain {

    private $client;
    private $batchSize;
    private $validSubdomainsFile;

    public function __construct($batchSize = 3)
    {
        $this->client = new Client([
            'proxy' => 'socks5h://127.0.0.1:9050',
            'timeout' => 60,
        ]);
        $this->batchSize = $batchSize;
        $this->validSubdomainsFile = (new Filex)->genFilename();
    }

    public function isTorRunning() 
    {
        $socket = @fsockopen('127.0.0.1', 9050, $errno, $errstr, 5);
        if(!$socket) {
            return false;
        } else {
            fclose($socket);
            return true;
        }
    }

    public function testSubdomains($domain, $subdomainList)
    {
        Logger::log("NOTICE! Results will be stored as {$this->validSubdomainsFile}");

        file_put_contents($this->validSubdomainsFile, '');

        $totalSubdomains = count($subdomainList);

        for ($i = 0; $i < $totalSubdomains; $i += $this->batchSize) {
            $promises = [];

            for ($j = 0; $j < $this->batchSize && ($i + $j) < $totalSubdomains; $j++) {
                $subdomain = $subdomainList[$i + $j];
                $currentUrl = "https://{$subdomain}.{$domain}";
                Logger::log("Testing Subdomain : {$currentUrl}");

                $promises[$currentUrl] = $this->client->requestAsync('GET', $currentUrl, ['timeout' => 10])
                    ->then(
                        function (ResponseInterface $res) use ($currentUrl) {
                            if ($res->getStatusCode() == 200) {
                                Logger::log("Subdomain {$currentUrl} is valid and has content.");
                                file_put_contents($this->validSubdomainsFile, "{$currentUrl}\n", FILE_APPEND);
                                return true;
                            }
                            Logger::log("Subdomain {$currentUrl} returned status code {$res->getStatusCode()}.");
                            return false;
                        },
                        function (RequestException $e) use ($currentUrl) {
                            Logger::errorLog("Subdomain {$currentUrl} is not valid or unreachable.");
                            return false;
                        }
                    );
            }

            $results = Utils::settle($promises)->wait();

            foreach ($results as $currentUrl => $result) {
                if ($result['state'] === 'fulfilled' && $result['value']) {
                    Logger::log("SUCCESS: subdomain {$currentUrl} is valid.");
                } else {
                    Logger::errorLog("ERROR: Subdomain {$currentUrl} is not valid.");
                }
            }
        }
    }
}
