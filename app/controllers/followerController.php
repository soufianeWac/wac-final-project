<?php
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../models/requestList.php';
require_once __DIR__.'/../tools/functions.php';

/*---------------------------------------------------------------------*/
/*             					//ROUTE AJAX ADD FOLLOW//               	 	   */
/*---------------------------------------------------------------------*/
$app->post('/add_follower{idU}/{idF}', function(Request $request, $idU, $idF) use($app)
{
	return process(addFollower($app, $idU, $idF));
});

$app->post('/unfollow{idU}/{idF}', function(Request $request, $idU, $idF) use($app)
{
	return process(unfollow($app, $idU, $idF));
});