<?php

require_once __DIR__ . '/../autoloader.php';
// use phpspider\core\phpspider;
use phpspider\web\phpspider;


$configs = require __DIR__ . '/configs/a5xiazai.php';

$configs['export'] = ['type' => 'csv', 'file' => './data/' . $configs['name'] . '.csv'];

var_dump($configs);

$spider = new phpspider($configs);

$spider->on_download_page = function ($page, $phpspider) {
    // $page_html = "<div id=\"comment-pages\"><span>5</span></div>";
    // $index = strpos($page['row'], "</body>");
    // $page['raw'] = substr($page['raw'], 0, $index) . $page_html . substr($page['raw'], $index);
    var_dump($page);
    return $page;
};

// var_dump($spider);
$spider->start();