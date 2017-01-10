<?php
require __DIR__ . '/vendor/autoload.php';

define('LINE_API', 'https://notify-api.line.me/api/notify');
define('LINE_TOKEN', '');

$url = 'https://premierleague.predictthefootball.com/minileague/weekly/8053/2016/';
$screen = new Screen\Capture($url);

// $screen->setClipWidth('200px');
// $screen->setClipHeight('200px');

$fileLocation = 'test';
$screen->save($fileLocation);
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
header('Content-Type:' . $screen->getImageType()->getMimeType());
header('Content-Length: ' . filesize($screen->getImageLocation()));
// $data = readfile($screen->getImageLocation());

$webserver = 'https://api.engineerball.com/football-predict-notify/'
# Send to LINE Bot API
lineNotify($screen->getImageLocation());

function lineNotify($data) {
	$queryData = array(
		'message' => 'Result',
		'imageThumbnail' => $webserver . $data,
		'imageFullsize' => $webserver . $data);
	$queryData = http_build_query($queryData,'&','$');
	$header = array(
		'http' => array(
			'method' => 'POST',
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n" . 'Authorization: Bearer ' . LINE_TOKEN . "\r\n" . "Content-Lenght" . strlen($queryData) . "\r\n",
			'content' => $queryData
			));
	$context = stream_context_create($header);
	$result = file_get_contents(LINE_API,FALSE,$context);
	$res = json_decode($result);
	return $res;
}
