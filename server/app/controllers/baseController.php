<?php

$app->get(BASE_URL, function() use($app)
{
	return 'home';
});