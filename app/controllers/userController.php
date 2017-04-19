<?php
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../models/requestList.php';
require_once __DIR__.'/../tools/functions.php';

/*---------------------------------------------------------------------*/
/*             					 		//ROUTE PROFIL PAGE//               	 	   */
/*---------------------------------------------------------------------*/
$app->match('/usr{id}', function(Request $request, $id) use($app)
{
	setlocale(LC_ALL, 'fr_FR.utf8','fra');
	$userSession = $app['session']->get('user');	
	$userProfil = userInfo($app, $id);
	$video = userVideo($app, $id);
	$lastVideo = lastUserVideo($app, $id);
	$dateVideo = date('j-m-Y H:i:s', strtotime($lastVideo['created_at']));
	$dateUser = strftime('%d %B %Y',  strtotime($userProfil['created_at']));

	if($id == $userSession['userInfo'])
	{
		$edit = true;
	}
	$category = getCategoryForVideos($app, $lastVideo['category_id']);
	// render page profil with info user and info connected user if true
	return $app['twig']->render('profil.twig', [
		'userConnected' => $userSession,
		'userPageProfil' => $userProfil,
		'lastVideo' => $lastVideo,
		'dateLastVideo' => formatDatePost($dateVideo),
		'dateUser' => $dateUser,
		'category' => $category['name'],
		'edit' => $edit
	]);
});

$app->match('/usr{id}/edition', function(Request $request, $id) use($app){
	$form = $app['form.factory']
    ->createBuilder(FormConstraintEditProfil::class)
    ->getForm();

  $form->handleRequest($request);

  if($form->isSubmitted())
  {
    $dataForm = $form->getData();
    if ($form->isValid()) {
			editAccount($app, $username, $avatar, $id);
    }
  }
	return $app['twig']->render('edition-profil.twig', [
	]);
});