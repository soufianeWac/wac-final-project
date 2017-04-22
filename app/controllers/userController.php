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


	$t2 = date('Y-m-j H:i:s');
	$t1 = date('Y-m-j H:i:s', strtotime($lastVideo['created_at']. "-3 week"));

 	$listOfRecentPost =  listRecentVideoUser($app, $id, $t1, $t2);

	if($id == $userSession['userInfo'])
	{
		$actionUser = true;
	}
	$form = $app['form.factory']->createBuilder(SearchFormConstraint::class)->getForm();
  $form->handleRequest($request);
  if($form->isSubmitted())
  {
  	$dataForm = $form->getData();
    if($form->isValid())
    {
    	$listUsers = searchUser($app, htmlentities($dataForm['profilsearch']));
    	return $app['twig']->render('liste-utilisateur.twig', ['listUsers' => $listUsers]);
    }
  }
	$category = getCategoryForVideos($app, $lastVideo['category_id']);
	// render page profil with info user and info connected user if true

	$callFuncFollowed = renderUserfollow($app, $id);

	$followed = [];
	$followed['str'] = "SUIVRE";
	$followed['value'] = false;

	if($callFuncFollowed[0]['user_id'] == $userSession['userInfo'] || $callFuncFollowed != null){
		$followed['str'] = "NE PLUS SUIVRE";
		$followed['value'] = true;
	}

	$listVideoUserFollowed = [];
	for ($i=0; $i < count(renderVideoFollower($app, $id)) ; $i++) { 
		$listVideoUserFollowed[] = renderVideoFollower($app, $id)[$i];
	}

	return $app['twig']->render('profil.twig', [
		'userConnected' => $userSession,
		'userPageProfil' => $userProfil,
		'lastVideo' => $lastVideo,
		'dateLastVideo' => formatDatePost($dateVideo),
		'dateUser' => $dateUser,
		'category' => $category,
		'actionUser' => $actionUser,
		'videoCount' => renderNbrOfVideoInCategory($app),
		'listOfRecentPost' => $listOfRecentPost,
		'followed' => $followed,
		'listVideoUserFollowed' => $listVideoUserFollowed,
		'form' => $form->createView()
	]);
});

$app->match('/usr{id}/edition', function(Request $request, $id) use($app){
  $userSession = $app['session']->get('user');

	if($userSession != null){
	if ($id != $userSession['userInfo']) {
  	return $app->redirect('/usr'.$userSession['userInfo'].'/edition');
  }
	$form = $app['form.factory']
    ->createBuilder(FormConstraintEditProfil::class)
    ->getForm();

  $form->handleRequest($request);
  $userInfo = userInfo($app, $userSession['userInfo']);
  if($form->isSubmitted())
  {
    $dataForm = $form->getData();

    if ($form->isValid()) {
    	$xlsDir = __DIR__.'/../../web/avatar/'.$userSession['userInfo'].'/';
    	$files = scandir($xlsDir);
	    $links = [];

	    foreach($files as $file)
	    {
	      if(is_file($xlsDir.$file))
	      {
	        unlink($xlsDir.$file);
	      }
	    }
    	if($dataForm['avatar'] != null)
			{	
	  		$pathOfFiles = __DIR__.'/../../web/avatar/'.$userSession['userInfo'];
	      $avatar = $dataForm['avatar']->getClientOriginalName();
				$movingFile = $dataForm['avatar']->move($pathOfFiles, $avatar);
    	}
			editAccount($app, $dataForm['username'], $avatar, $id);
    }
  }
	return $app['twig']->render('edition-profil.twig', [
		'form' => $form->createView(),
		'userInfo' => $userInfo
	]);
	}
	else
	{
		return 'Vous devez être connécté';
	}
});

$app->match('/delete_usr{id}', function(Request $request, $id) use($app)
{
	$userSession = $app['session']->get('user');

	if($id == $userSession['userInfo'])
	{
		$dirA = __DIR__.'/../../web/avatar/'.$userSession['userInfo'];//select folder of avatar user
		$dirB = __DIR__.'/../../web/video_h/'.$userSession['userInfo'];//select folder of video user
		rmFolder($dirA);
		rmFolder($dirB);
		
		deleteAccount($app, $id); //delete info user
		deleteAllVideoUser($app, $id); //delete video user
		deleteAllComOfUser($app, $id); //delete user-related comment
		$app['session']->remove('user'); //clear session
		return $app->redirect('/accueil'); //redirect to home
	}
	else{
		return $app['twig']->render('404.twig');
	}
});