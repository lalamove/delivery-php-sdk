<?php
namespace src;
use Dotenv\Dotenv;
use Lalamove\Quotation;

require("Client.php");
require("Config.php");
require("Quotation.php");
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = new Config($_ENV["HOST"], $_ENV["API_PUBLIC_KEY"], $_ENV["API_PRIVATE_KEY"], "HK");
$newClient = new Client($config);
var_dump($newClient);
$stop1 = ["coordinates" => [
    "lat" => "22.3353139",
    "lng" => "114.1758402"
],
    "address" => "Innocentre, 72 Tat Chee Ave, Kowloon Tong"];

$stop2 = ["coordinates" => [
    "lat" => "22.3203648",
    "lng" => "114.169773"
],
    "address" => "HOLLYWOOD PLAZA, Mong Kok"];

$quotationPayload = Quotation::make([$stop1, $stop2], "COURIER", "en_HK");
$quotation = $newClient->quotations->create($quotationPayload);

echo $quotation;