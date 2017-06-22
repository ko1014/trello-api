<?php
include(__DIR__. '/vendor/autoload.php');
use Trello\Client;

$api_key = '95ebcae780e06502659f77b2ec21bebc';
$access_token = '8db3a191c8b9c5d5db24aaadf47c4e69292c9b8297da6710f53';


$client = new Client();

$client->authenticate($api_key, $access_token, Client::AUTH_URL_CLIENT_ID);

//$boards = $client->members()->boards()->all('ko1014');


