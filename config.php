<?php
/**
 * User: Roy Sinclair
 * Date: 2018/10/11
 * Time: 11:28
 */

//set timezone
date_default_timezone_set("Africa/Johannesburg");

// Report all PHP errors
error_reporting(-1);
// Set Environment
$ENV = "PRODUCTION"; // Set SANDBOX or PRODUCTION

// Load database connect
require ('dbconnect.php');

/**
 * ANDROID Push
 */
$googleKey = '';

/**
 * iOS Push
 */
// Using Autoload all classes are loaded on-demand
require_once "ApnsPHP/Autoload.php";

// Instantiate a new ApnsPHP_Push object
// SANDBOX
if($ENV == "SANDBOX") {
    $push = new ApnsPHP_Push(
        ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
        "keys/sandbox.pem"
    );
}
// PRODUCTION
if($ENV == "PRODUCTION") {
    $push = new ApnsPHP_Push(
        ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
        "keys/production.pem"
    );
}

// Set the Provider Certificate passphrase
$passphrase = "";
if(isset($passphrase)) {
    $push->setProviderCertificatePassphrase($passphrase);
}

// Set the Root Certificate Autority to verify the Apple remote peer
$push->setRootCertificationAuthority("keys/entrust_root_certification_authority.pem");