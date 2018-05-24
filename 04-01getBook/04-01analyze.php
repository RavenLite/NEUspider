<?php
require  '../vendor/autoload.php';
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

function showcourse($response){
    $data    = []; //Store
    $crawler = new Crawler();
    $crawler->addHtmlContent($response);

    try {
        $data['name'] = $crawler->filterXPath('/html/body/div[@id=\'brief\']
        /table/tbody/tr[1]/td[1]/table[@class=\'items\'][1]/tbody/tr/td[@class=
        \'cover\'][1]/a[@id=\'NEU01000219238\']/img/@src')->text();
    } catch (\Exception $e) {
        echo "No nodes  ";
    }
    print_r($data);
    //echo $response;
}
?>