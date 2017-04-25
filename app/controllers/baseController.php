<?php
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../models/requestList.php';
require_once __DIR__.'/../tools/functions.php';

$app->match('/', function(Request $request) use($app)
{
	$countVideosInCat = renderNbrOfVideoInCategory($app);
	$form = $app['form.factory']->createBuilder(SearchVideoFormConstraint::class)->getForm();
  $form->handleRequest($request);
  if($form->isSubmitted())
  {
  	$dataForm = $form->getData();
    if($form->isValid())
    {
    	$listVideos = searchVideo($app, htmlentities($dataForm['videosearch']));
    	return $app['twig']->render('liste-video.twig', [
    		'listVideos' => $listVideos,
    		'countVideosInCat' => $countVideosInCat
    	]);
    }
  }
	return $app['twig']->render('pre-accueil.twig', ['form' => $form->createView()]);
});