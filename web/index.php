<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['twitter.key'] = '';
$app['twitter.secret'] = '';
$app['twitter.token'] = '';
$app['twitter.token.secret'] = '';

include_once __DIR__.'/config.php';

$app['twitter.service'] = $app->share(function ($app) {
    return new Twitter($app['twitter.key'], $app['twitter.secret'], $app['twitter.token'], $app['twitter.token.secret']);
});

$app['keggers'] = array('paperkeg', 'slim', 'dale_a', 'jonesylovesbeer'); 

$app->get('/', function () use ($app) {	
	return $app->sendFile(__DIR__.'/../src/index.html');
});

$app->get('/data', function () use ($app) {
	$keggers = $app['keggers'];
	array_walk($keggers, quantifyPopularity, $app['twitter.service']);
	return $app->json($keggers);
});

$app->run();

function quantifyPopularity(&$value, $key, $twitter) {
	$info   = $twitter->cachedRequest('users/show', array('screen_name' => $value, 'include_entities' => false));
	$photo  = str_replace('normal', 'bigger', $info->profile_image_url);
	$points = 0;
	$points += ($info->followers_count * 0.25);
	$points += ($info->friends_count * 0.75);
	$points += ($info->listed_count * 0.50);
	
	$search = $twitter->cachedRequest('search/tweets', array(
		'q' => '@'.$value.' AND @paperkeg',
		'include_entities' => false,
		'result_type' => 'recent'
	));
	$points += count($search->statuses);
	
	$value = array(
		'name' => $info->screen_name,
		'points' => $points, 
		'photo' => $photo,
	);
}