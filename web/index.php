<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/form.classes.php';
date_default_timezone_set('Europe/Paris');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormError;

$app = new Silex\Application();

$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('fr'),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/views',
  'twig.options' => array('debug' => true),
));
$app['debug'] = true;

require_once __DIR__.'/../app/configDev.php';
require_once __DIR__.'/../app/controllers/baseController.php';
require_once __DIR__.'/../app/controllers/userController.php';
require_once __DIR__.'/../app/controllers/inscriptionController.php';
require_once __DIR__.'/../app/controllers/connexionController.php';
require_once __DIR__.'/../app/controllers/homeController.php';
require_once __DIR__.'/../app/controllers/videoController.php';
require_once __DIR__.'/../app/controllers/categoryController.php';

$app->error(function(\Exception $e, Request $request) use ($app) {
  $app['request'] = $request;
  if ($e instanceof NotFoundHttpException) {
    return $app['twig']->render('404.twig');
  }
});

$app->run();
