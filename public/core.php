<?php
require '../vendor/autoload.php';
require '../config/config.php';

use classes\Logger;
use GuzzleHttp\Client;
use classes\Display;
use classes\Sanatize;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Utils;

Display::menu();

if ($argc < 3) {
    Logger::log('Usage: core.php <base_domain> <subdomains_list_file>');
    die();
}

$domain = $argv[1];
$domain = Sanatize::sanatizeUrl($domain);
$subdomainList = file($argv[2], FILE_IGNORE_NEW_LINES);

$client = new Client();
$batchSize = 3; 
$totalSubdomains = count($subdomainList);
$validSubdomainsFile = 'valid_subdomains.txt';

Logger::log("NOTICE! Results will be stored as {$validSubdomainsFile}");

file_put_contents($validSubdomainsFile, '');

for ($i = 0; $i < $totalSubdomains; $i += $batchSize) {
    $promises = [];

    
    for ($j = 0; $j < $batchSize && ($i + $j) < $totalSubdomains; $j++) {
        $subdomain = $subdomainList[$i + $j];
        $currentUrl = "https://{$subdomain}.{$domain}";
        Logger::log("Testing Subdomain : {$currentUrl}");

        $promises[$currentUrl] = $client->requestAsync('GET', $currentUrl, ['timeout' => 10])
            ->then(
                function (ResponseInterface $res) use ($currentUrl, $validSubdomainsFile) {
                    if ($res->getStatusCode() == 200) {
                        Logger::log("Subdomain {$currentUrl} is valid and has content.");
                        file_put_contents($validSubdomainsFile, "{$currentUrl}\n", FILE_APPEND);
                        return true;
                    }
                    Logger::log("Subdomain {$currentUrl} returned status code {$res->getStatusCode()}.");
                    return false;
                },
                function (RequestException $e) use ($currentUrl) {
                    Logger::log("Subdomain {$currentUrl} is not valid or unreachable.");
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