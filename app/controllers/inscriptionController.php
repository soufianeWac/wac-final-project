<?php
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../models/requestList.php';

$app->match('/inscription', function(Request $request) use($app){
  $countVideosInCat = renderNbrOfVideoInCategory($app);
  if($usrSess = $app['session']->get('user'))
  {
    return $app->redirect($_SERVER['HTTP_REFERER']);
  }
  $form = $app['form.factory']->createBuilder(FormConstraintInscription::class)->getForm();
  $form->handleRequest($request);
  if($form->isSubmitted())
  {
    $dataForm = $form->getData();
    $sql_row = 'SELECT * FROM `users` WHERE `mail` = :user_mail';
    $row = $app['db']->prepare($sql_row);
    $row->bindValue('user_mail', $dataForm['mail']);
    $row->execute();
    $result_rows = $row->fetchAll(\PDO::FETCH_ASSOC);

    if(count($result_rows) > 1) {
      $form->get('mail')->addError(new \Symfony\Component\Form\FormError('Cette adresse existe déjà'));
    }

    if($dataForm['password'] != $dataForm['confirm_password']){
      $form->get('confirm_password')->addError(new \Symfony\Component\Form\FormError('Les mots de passe doivent être identique'));
    }

    if($form->isValid()){
      //generate token for validation of user subscribe
      //$sess = $app['session']->set('user', array('user_token' => $token);
      $token = random_bytes(4);
      $token = strtoupper(bin2hex($token));
      $avatar = 'views/img/avatar.png';

      //step1 - insert data of user in db with token
      userSubscribe($app, $dataForm, $token, $avatar);
      $lastUserId = $app['db']->lastInsertId();

      //step2 - insert token generate in db
      userValidation($app, $token);

      //step3 - send mail with token
      sendMail($dataForm['mail'], $token);

      //step4 - redirect to form validation subscribe
      return $app->redirect('/validation'.$lastUserId);
    }
  }
  $formView = ['participFormView' => $form->createView(), 'countVideosInCat' => $countVideosInCat];
  return $app['twig']->render('inscription.twig', $formView);
});

$app->match('/validation{id}', function(Request $request, $id) use($app)
{
  $countVideosInCat = renderNbrOfVideoInCategory($app);
  $validation = userInfo($app, $id);
  if($validation['valid'] != 0 || $validation == false)
  {
    return $app->redirect('/');
  }
  else
  {
    $form = $app['form.factory']->createBuilder(TokenFormConstraint::class)->getForm();
    $form->handleRequest($request);
    if($form->isSubmitted())
    {
      $dataForm = $form->getData();

      if($form->isValid())
      {
        if($validation == true && $dataForm['token'] == $validation['token_validation'])
        {
          validUser($app, true, $dataForm['token']);
          //step5 - create session and redirect to profil page
          $app['session']->set('user', array('userInfo' => $id));
          return $app->redirect('/usr'.$id);
        }
        else{
          $form->get('token')->addError(new \Symfony\Component\Form\FormError('Ce code n\'est pas valide'));
        }
      }
    }
    return $app['twig']->render('token.twig', ['form' => $form->createView(), 'countVideosInCat' => $countVideosInCat]);
  }
});