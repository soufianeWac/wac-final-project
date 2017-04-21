<?php 
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;

$app->get('/category{id}', function(Request $request, $id) use ($app)
{
	$listVideo = listVideoCategory($app, $id);
	$idCategory = $id;
	return $app['twig']->render('categorie.twig', [
		'listingVid' => $listVideo, 
		'idCat' => $idCategory
	]);
});

$app->post('/category{id}', function(Request $request, $id) use($app)
{
	$page = $_POST['page'];
  $resSubmitForm['listVid'] = listVideoCategory($app, $id, $page);
  return json_encode($resSubmitForm);
});