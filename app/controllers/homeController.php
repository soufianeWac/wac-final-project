<?php
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;

$app->match('/accueil', function() use($app)
{
	return $app['twig']->render('accueil.twig');
});