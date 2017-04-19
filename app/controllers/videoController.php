<?php
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../models/requestList.php';
require_once __DIR__.'/../tools/functions.php';

$app->get('/video{id}', function(Request $request, $id) use($app)
{
	setlocale(LC_ALL, 'fr_FR.utf8','fra');
	$video = video($app, $id);
	$category = getCategoryForVideos($app, $video['category_id']);
	$userSession = $app['session']->get('user');	
	$dateVideo = date('j-m-Y H:i:s', strtotime($video['created_at']));

	$form = $app['form.factory']
  ->createBuilder(CommentaireFormConstraint::class)
  ->getForm();

  $form->handleRequest($request);

  $commentaire = selectCom($app, $id, 1);
  $commentInPage = countCom($app, $id);

	return $app['twig']->render('video.twig', [
		'userConnected' => $userSession,
		'infos' => $video,
		'category' => $category['name'],
		'date' => formatDatePost($dateVideo),
		'formCommentaire' => $form->createView(),
		'comment' => $commentaire,
    'nbrCom' => $commentInPage
	]);
});

$app->post('/video{id}', function(Request $request, $id) use ($app){
  $page = $_POST['page'];
  $resSubmitForm['listCom'] = selectCom($app, $id, $page);
  $resSubmitForm['session'] = $app['session']->get('user')['userInfo'];
  return json_encode($resSubmitForm);
});
/*---------------------------------------------------------------------*/
/*             					 		 //ROUTE POST AJAX CORN//               	 */
/*---------------------------------------------------------------------*/
$app->post('/add_com', function(Request $request) use($app)
{
	$form = $app['form.factory']
    ->createBuilder(CommentaireFormConstraint::class)
    ->getForm();
  $form->handleRequest($request);

  //check all field of form and return error of this form if any exist
  $errorsForm = $app['validator']->validate($form);
  //give content of form
  $dataForm = $form->getData();
  //init variable to send after in js
  $resSubmitForm = [];
  $sessInfo = $app['session']->get('user');
  $userProfil = userInfo($app, $sessInfo['userInfo']);

  if (count($errorsForm) > 0)
  {
    $resSubmitForm['access'] = 'not valid';
    $errorStr = '';
    foreach ($errorsForm as $error)
    {
      //result is error message of field form
      $errorStr .= $error->getMessage()."\n";
    }
    $resSubmitForm['errors'] = $errorStr;
  }
  else
  {
    $resSubmitForm['access'] = 'valid';
  	comSubscribe($app, $dataForm, $userProfil['username'], $request->get('video_id'), $sessInfo['userInfo']);
    $lastInsertId = $app['db']->lastInsertId();
    $resSubmitForm['page'] = 1;
  	$resSubmitForm['listCom'] = selectLastCom($app, $request->get('video_id'), $lastInsertId);
  }
  return json_encode($resSubmitForm);
});

$app->match('/delete_com{id}', function(Request $request, $id) use($app){
	deleteCom($app, $id, $app['session']->get('user')['userInfo']);
	return json_encode('is_delete');
});

$app->match('/delete_video{id}', function(Request $request) use($app){
	
});


/*---------------------------------------------------------------------*/
/*             					 		 //ROUTE ADD VIDEO//               	 	     */
/*---------------------------------------------------------------------*/
$app->match('/usr{id}/add_video', function(Request $request, $id) use($app)
{
	 $form = $app['form.factory']
    ->createBuilder(VideoFormConstraint::class)
    ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted())
    {
      $dataForm = $form->getData();
      if($form->isValid())
      {
      	videoSubscribe($app, $dataForm, $id);
      	$lastVideoId = $app['db']->lastInsertId();

      	$categoryName = getCategory($app, $dataForm['category']);

      	videoCategory($app, $categoryName['id'], $lastVideoId);
      
        $pathOfFiles = __DIR__.'/../../web/video_h/'.$id;
        $newFileName = $dataForm['videoFile']->getClientOriginalName();
				$movingFile = $dataForm['videoFile']->move($pathOfFiles, $newFileName);

				return $app->redirect('/video'.$lastVideoId);
      }
    }
    $formView = ['form' => $form->createView()];
    return $app['twig']->render('ajouter-video.twig', $formView);
});