<?php
/**
 * https://github.com/Wruczek/TeamSpeak-HiddenPermsChecker
 * Made by Wruczek (github.com/Wruczek)
 * Licensed under MIT
 */

require_once __DIR__ . "/vendor/autoload.php";

// Edit this
$host   = "127.0.0.1";
$login  = "serveradmin";
$passwd = "password";
$sport  = "9987";
$qport  = "10011";
// =========

try {
    $tsNodeHost = TeamSpeak3::factory("serverquery://$host:$qport");
    $tsNodeHost->login($login, $passwd);
    $tsServer = $tsNodeHost->serverGetByPort($sport);
} catch (Exception $e) {
    msg("Exception while connecting to server: {$e->getMessage()}");
    msg(); // New line
    echo $e;
    exit;
}

// A little hack for PHPStorm to let it know that
// tsServer is a TeamSpeak3_Node_Server instance.
// It adds code inspection and code completion.
if(!($tsServer instanceof TeamSpeak3_Node_Server)) {
    exit;
}

msg("Loading users, 100 at a time...");

$clientslist = [];

// Get number of clients known by TeamSpeak server
$total = array_values($tsServer->clientListDb(0, 1))[0]["count"];

msg("Detected $total users, loading started");

$offset = 0;

// Load users
while(($count = count($clientslist)) < $total) {
    msg(" > Loading part $count / $total");
    $clientslist = array_merge($clientslist, $tsServer->clientListDb($count, 100));
}

msg("Loaded " . count($clientslist) . " out of $total users.");
msg("Scanning, this might take a few minutes...");
msg();

$i = 0;

foreach ($clientslist as $client) {
    try {
        $dbid = $client["cldbid"];
        $nickname = $client["client_nickname"];
        $uuid = $client["client_unique_identifier"];

        // Try to get list if unique permissions.
        // If user don't have any custom permissions set,
        // this will throw an "empty database result"
        // exception and stop executing this block of code
        $tsServer->clientPermList($dbid);

        // If there's no exception, this user have some hidden permissions set.
        msg("Client UUID \"$uuid\" (\"$nickname\") have hidden permissions");
        $i++;
    } catch (Exception $e) {
        // Skip "empty database result set" exceptions
        if($e->getCode() !== 1281) {
            echo $e;
            exit;
        }
    }
}

if($i > 0) msg();
msg("Finished. Found $i users with hidden permissions.");

// Util
function msg($msg = "") {
    echo $msg . PHP_EOL;
}
