<?php

use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$mg = new \Eddy\ModularGrid\Wrapper();

$url = $mg->url->forPedal('elektron-overhub');
// $client = new \GuzzleHttp\Client();
// $res = $client->request('GET', $url);
// $body = $res->getBody()->getContents();
// file_put_contents('html/test2.html', $body);
$body = file_get_contents('html/test1.html');
dd($url);
dd((new \Eddy\ModularGrid\Crawler\GetLink(new Crawler($body)))->scan());
