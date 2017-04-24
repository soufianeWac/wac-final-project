<?php 
namespace Silex\Form;

use Symfony\Component\HttpFoundation\Request;

$app->match('/connexion', function(Request $request) use ($app)
{
  $app['request'] = $request;

  //verify if session exist, if is defined redirect to login else set session and go to process of connexion
  if($usrSess = $app['session']->get('user'))
  {
    return $app->redirect('/accueil');
  }
  $form = $app['form.factory']
  ->createBuilder(ConnexionFormConstraint::class)
  ->getForm();

  $form->handleRequest($request);

  $errorsForm = $app['validator']->validate($form);
  if($form->isSubmitted())
  {
    $dataForm = $form->getData();
    $password = md5($dataForm['password'].SALT);

    $user = $app['db']->fetchAssoc('SELECT * FROM users WHERE mail = ?', [$dataForm['login']]);
    if(count($errorsForm) <= 0){
      if($user['password'] != $password || $user == false) {
        $form->get('password')->addError(new \Symfony\Component\Form\FormError('Le login ou le mot de passe est incorrect'));
      }
    }
    if($form->isValid())
    {
      if($user['valid'] == true)
      {
        $app['session']->set('user', array('userInfo' => $user['id']));
        return $app->redirect('/usr'.$user['id']);
      }
      else
      {
        $form->get('password')->addError(new \Symfony\Component\Form\FormError('Vous devez valider votre compte'));
      }
    }
  }
  return $app['twig']->render('/connexion.twig', array('form' => $form->createView()));
  
});

$app->match('/deconnexion', function(Request $resquest) use ($app){
  $app['session']->remove('user');
  return $app->redirect($_SERVER['HTTP_REFERER']);
});