<?php

use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

// $mg = new \Eddy\Crawlers\ModularGrid\Wrapper();
// $mn = new \Eddy\Crawlers\Mannys\Client();
$pe = new \Eddy\Crawlers\PE();

if (!isset($argv[1])) {
    echo 'No category specified. Exiting...' . PHP_EOL;
    exit(0);
}

if ($argv[1] === 'list') {
    echo 'Categories:' . PHP_EOL . PHP_EOL;
    foreach ($pe->url::SHORTCUTS as $key => $val) {
        echo $key . PHP_EOL;
    }
    exit(0);
}

$cat = $argv[1];

// $mn = 'for' . ucfirst($cat);
if (!isset($pe->url::SHORTCUTS[$cat])) {
    throw new \RuntimeException('Unknown category: ' . $cat);
}

$u = $pe->url->make($pe->url::SHORTCUTS[$cat]);
// dd($u);
// $url = $mg->url->forPedal('elektron-overhub');
// $client = new \GuzzleHttp\Client();
$res = $pe->client->request('GET', $u);
echo 'Response received' . PHP_EOL;
$body = $res->getBody()->getContents();

$lastPg = $pe->crawler->getLastPage($body);

echo 'Located ' . ($lastPg ?? 1) . ' total page' . ($lastPg && $lastPg === 1 ? '' : 's') . PHP_EOL;

$data = $pe->crawler->scanCategoryPage($body);

echo 'Page 1' . PHP_EOL;

if ($lastPg && $lastPg > 1) {
    $i = 2;
    
    while ($i <= $lastPg) {
        $u = $pe->url->make($pe->url::SHORTCUTS[$cat], $i);
        $res = $pe->request('GET', $u);
        echo 'Response received' . PHP_EOL;
        $body = $res->getBody()->getContents();
        array_push($data, ...$pe->crawler->scanCategoryPage($body));
        echo 'Page ' . $i . PHP_EOL;
        $i++;
    }
}

$dataCount = count($data);

echo 'Extracted ' . $dataCount . ' total entr' . ($dataCount === 1 ? 'y' : 'ies') . '.' . PHP_EOL;

$json = json_encode($data, JSON_PRETTY_PRINT);


// file_put_contents('json/' . $cat . '.json', $json);
echo 'Stored JSON for category ' . $cat . PHP_EOL;
// file_put_contents('html/pe.html', $body);
// $crawler = $mg->crawl($body);

// dd($crawler->imageURLs());

// dd($mg->url::extractCategory('https://www.modulargrid.net/e/racks/view/2168167'));

// file_put_contents('html/test2.html', $body);
// $body = file_get_contents('html/test1.html');
// dd((new \Eddy\ModularGrid\Crawler\GetLink(new Crawler($body)))->scan());

// $json = json_decode(file_get_contents('html/nopedals.json'), true);

// $ids = [];
// $vendors = [];
// $modules = $json['rack']['Module'];

// $filteredModules = array_filter($modules, function ($m) {
//     if (!isset($m['id']) || isset($ids[$m['id']])) return false;
//     $ids[$m['id']] = true;
//     return true;
// });

// $preparedModules = array_map(function ($m) {
//     $module = [
//         'id' => $m['id'],
//       'name' => $m['name'],
//       'slug' => $m['slug'],
//       'size' => [
//         'hp' => $m['te'] || null,
//         'depth' => $m['depth'] || null,
//         'width' => $m['width'] || null,
//         'height' => $m['height'] || null,
//       ],
//       'description' => $m['description'],
//       'price' => [
//         'eur' => $m['price_eur'],
//         'usd' => $m['price_usd'],
//       ],
//       'power' => [
//         'currentNeg' => $m['current_min'],
//         'currentPos' => $m['current_plus'],
//         'current5v' => $m['current5v'],
//       ],
//       'vendor' => $m['Vendor']
//         ? [ ...$m['Vendor'], 'id' => $m['vendor_id'] ]
//         : [ 'name' => null, 'id' => $m['vendor_id'] ],
//     ];
//     if ($m['vendor_id'] && !isset($vendors[$m['vendor_id']])){
//         $vendors[$m['vendor_id']] = $module['vendor'];
//     }
//     return $module;
// }, $filteredModules);

// dd($preparedModules);
