<?php
require 'vendor/autoload.php';

$pb = new \Eddy\Crawlers\PB();
// (new \Eddy\Crawlers\Shared\FS\StoreRobots(\Eddy\Crawlers\Shared\FS\Factory::make()))
//     ->save($pb->url::robots(), $pb->robots->toString());

// dd((new \Eddy\Crawlers\Shared\InitRobots(
//     $pb->client,
//     \Eddy\Crawlers\Shared\FS\Factory::make()
// ))->from($pb->url::robots()));
$productPath = '5391-Phase-Plant';
$pe = new \Eddy\Crawlers\PE();
$mg = new \Eddy\Crawlers\MG();
// $crawler = new \Eddy\Crawlers\PluginBoutique\Crawler();

$url = $pb->url->product($productPath);
$res = $pb->client->get($url, $pb->requestConfig());
if ($res->getStatusCode() !== 200) {
    echo 'Bad url!' . PHP_EOL;
    exit(1);
}
$html = $res->getBody()->getContents();
// $html = file_get_contents('html/0-page.html');
// $result = $crawler->getProductMetadata($html);
$result = $pb->crawler->getProductMetadata($html);
file_put_contents('json/phaseplant.json', json_encode($result, JSON_PRETTY_PRINT));
dd($result);
// $sitemapUrl = $pb->robots->getAgent()->getSitemap();

// $res = $pb->guzzle->get($sitemapUrl);
// $xml = $res->getBody()->getContents();
// file_put_contents('json/pb-sitemap.xml', $xml);
// $stream = fopen('json/pb-sitemap.xml', 'r');
// $xml = file_get_contents('json/pb-sitemap.xml');
// $parser = xml_parser_create();
// while (($data = fread($stream, 16384))) {
//     xml_parse($parser, $data); // parse the current chunk
// }
// xml_parse($parser, '', true); // finalize parsing
// xml_parser_free($parser);
// $t = simplexml_load_file('json/pb-sitemap.xml');
// $id = 0;
// foreach ($t as $node) {
//     if (isset($node->loc)
//         && ($node->loc instanceof SimpleXMLElement)
//         && str_starts_with($node->loc, $pb->url->product)
//     ) {
//         $res = $pb->guzzle->get((string) $node->loc, [
//             'delay' => 3000
//         ]);

//         if ($res->getStatusCode() !== 200) {
//             continue;
//         }

//         $body = $res->getBody()->getContents();

//         file_put_contents('html/' . $id . '-page.html', $body);
//         echo 'Saved product #' . $id . PHP_EOL;

//         $id++;
//     }
// }
// $res = $mg->ping($mg->url->forEurorack('alm-busy-circuits-pamela-s-pro-workout'), false);
// $result = $pe->crawler->getProductJson($body);
// dd($res);
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
