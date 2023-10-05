<?php
require 'vendor/autoload.php';

$pe = new \Eddy\Crawlers\PE();

$url = $pe->url->forItem('sea-machine-v2');

$res = $pe->client->request('GET', $url);
if ($res->getStatusCode() === 404) {
    echo 'Product page not found for URL: ' . $url . PHP_EOL;
    exit(0);
}

$body = $res->getBody()->getContents();

$result = $pe->crawler->getProductJson($body);
dd($result);
// if (!isset($argv[1])) {
//     echo 'No category specified. Exiting...' . PHP_EOL;
//     exit(0);
// }

// if ($argv[1] === 'list') {
//     echo 'Categories:' . PHP_EOL . PHP_EOL;
//     foreach ($pe->url::SHORTCUTS as $key => $val) {
//         echo $key . PHP_EOL;
//     }
//     exit(0);
// }

// $cat = $argv[1];

// if (!isset($pe->url::SHORTCUTS[$cat])) {
//     throw new \RuntimeException('Unknown category: ' . $cat);
// }

// $u = $pe->url->make($pe->url::SHORTCUTS[$cat]);

// $res = $pe->client->request('GET', $u);
// echo 'Response received' . PHP_EOL;
// $body = $res->getBody()->getContents();

// $lastPg = $pe->crawler->getLastPage($body);

// echo 'Located ' . ($lastPg ?? 1) . ' total page' . ($lastPg && $lastPg === 1 ? '' : 's') . PHP_EOL;

// $data = $pe->crawler->scanCategoryPage($body);

// echo 'Page 1' . PHP_EOL;

// if ($lastPg && $lastPg > 1) {
//     $i = 2;
    
//     while ($i <= $lastPg) {
//         $u = $pe->url->make($pe->url::SHORTCUTS[$cat], $i);
//         $res = $pe->request('GET', $u);
//         echo 'Response received' . PHP_EOL;
//         $body = $res->getBody()->getContents();
//         array_push($data, ...$pe->crawler->scanCategoryPage($body));
//         echo 'Page ' . $i . PHP_EOL;
//         $i++;
//     }
// }

// $dataCount = count($data);

// echo 'Extracted ' . $dataCount . ' total entr' . ($dataCount === 1 ? 'y' : 'ies') . '.' . PHP_EOL;

// $json = json_encode($data, JSON_PRETTY_PRINT);


// // file_put_contents('json/' . $cat . '.json', $json);
// echo 'Stored JSON for category ' . $cat . PHP_EOL;
