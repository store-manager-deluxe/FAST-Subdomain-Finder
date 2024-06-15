<?php

namespace Classes;

use classes\Logger;
use GuzzleHttp\Client;
use classes\Filex;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Utils;
use classes\Masking\Headers;

class Subdomain {

    private $client;
    private $batchSize;
    private $validSubdomainsFile;

    public function __construct($batchSize = 10, Headers $header)
    {
        $userAgent = $header->randomAgent(function() {
            $url = 'https://gist.githubusercontent.com/pzb/b4b6f57144aea7827ae4/raw/cf847b76a142955b1410c8bcef3aabe221a63db1/user-agents.txt';
            $content = @file_get_contents($url); // Use @ to suppress warnings
            if ($content === false) {
                return []; // Return an empty array if the URL cannot be fetched
            }
            return array_filter(array_map('trim', explode("\n", $content)));
        });

        $this->client = new Client([
            'proxy' => 'socks5h://127.0.0.1:9050',
            'timeout' => 60,
            'headers' => [
                'User-Agent' => $userAgent,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
                'DNT' => '1' // Do Not Track request header
            ]
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
        Logger::log(">>> NOTICE! Results will be stored in: {$this->validSubdomainsFile} <<<");

        file_put_contents($this->validSubdomainsFile, '');

        $totalSubdomains = count($subdomainList);

        for ($i = 0; $i < $totalSubdomains; $i += $this->batchSize) {
            $promises = [];

            for ($j = 0; $j < $this->batchSize && ($i + $j) < $totalSubdomains; $j++) {
                $subdomain = $subdomainList[$i + $j];
                $currentUrl = "https://{$subdomain}.{$domain}";
                $proxy = $this->logProxy();
                Logger::log("[*] Proxy  = [ {$proxy} ]  Subdomain : {$currentUrl}");

                $promises[$currentUrl] = $this->client->requestAsync('GET', $currentUrl, ['timeout' => 10])
                    ->then(
                        function (ResponseInterface $res) use ($currentUrl) {
                            if ($res->getStatusCode() == 200) {
                                Logger::log("[+] Subdomain {$currentUrl} is valid and has content.");
                                file_put_contents($this->validSubdomainsFile, "{$currentUrl}\n", FILE_APPEND);
                                return true;
                            }
                            Logger::log("[-] Subdomain {$currentUrl} returned status code {$res->getStatusCode()}.");
                            return false;
                        },
                        function (RequestException $e) use ($currentUrl) {
                            Logger::errorLog("[-] Subdomain {$currentUrl} is not valid or unreachable.");
                            return false;
                        }
                    );
            }

            $results = Utils::settle($promises)->wait();

            foreach ($results as $currentUrl => $result) {
                if ($result['state'] === 'fulfilled' && $result['value']) {
                    Logger::log("[+] SUCCESS: Subdomain {$currentUrl} is valid.");
                } else {
                    Logger::errorLog("[-] ERROR: Subdomain {$currentUrl} is not valid.");
                }
            }
        }
    }

    public function logProxy(): string
    {
        $response = $this->client->request('GET', 'https://icanhazip.com');
        $proxyIp = trim($response->getBody()->getContents());
        return $proxyIp;
    }
}