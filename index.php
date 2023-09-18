<?php

use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$mg = new \Eddy\ModularGrid\Wrapper();

// $url = $mg->url->forPedal('elektron-overhub');
// $client = new \GuzzleHttp\Client();
// $res = $client->request('GET', $url);
// $body = $res->getBody()->getContents();
// file_put_contents('html/test2.html', $body);
// $body = file_get_contents('html/test1.html');
// dd((new \Eddy\ModularGrid\Crawler\GetLink(new Crawler($body)))->scan());

$json = json_decode(file_get_contents('html/nopedals.json'), true);

$ids = [];
$vendors = [];
$modules = $json['rack']['Module'];

$filteredModules = array_filter($modules, function ($m) {
    if (!isset($m['id']) || isset($ids[$m['id']])) return false;
    $ids[$m['id']] = true;
    return true;
});

$preparedModules = array_map(function ($m) {
    $module = [
        'id' => $m['id'],
      'name' => $m['name'],
      'slug' => $m['slug'],
      'size' => [
        'hp' => $m['te'] || null,
        'depth' => $m['depth'] || null,
        'width' => $m['width'] || null,
        'height' => $m['height'] || null,
      ],
      'description' => $m['description'],
      'price' => [
        'eur' => $m['price_eur'],
        'usd' => $m['price_usd'],
      ],
      'power' => [
        'currentNeg' => $m['current_min'],
        'currentPos' => $m['current_plus'],
        'current5v' => $m['current5v'],
      ],
      'vendor' => $m['Vendor']
        ? [ ...$m['Vendor'], 'id' => $m['vendor_id'] ]
        : [ 'name' => null, 'id' => $m['vendor_id'] ],
    ];
    if ($m['vendor_id'] && !isset($vendors[$m['vendor_id']])){
        $vendors[$m['vendor_id']] = $module['vendor'];
    }
    return $module;
}, $filteredModules);

dd($preparedModules);
