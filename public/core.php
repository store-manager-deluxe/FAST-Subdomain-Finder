<?php

require '../vendor/autoload.php';
require '../config/config.php';

use classes\Display;
use classes\Sanitize;
use classes\Logger;
use classes\Subdomain;
use classes\Masking\Headers;
use classes\TorController;

Display::menu();

if ($argc < 3) {
    Logger::log('Usage: core.php <base_domain> <subdomains_list_file>');
    die();
}

$domain = $argv[1];
$domain = Sanitize::sanitizeUrl($domain);
$subdomainList = file($argv[2], FILE_IGNORE_NEW_LINES);

$headers = new Headers();
$subdomainTester = new Subdomain(10, $headers);

if(!$subdomainTester->isTorRunning()) {
    Logger::errorLog('ERROR: Tor service is not running. Please start the Tor service.');
    die();
}

$subdomainTester->testSubdomains($domain, $subdomainList);
