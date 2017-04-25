<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/db/migrations-db.php';

$app->register(new Silex\Provider\DoctrineServiceProvider(),
  array(
    'db.options' => $GLOBALS['database']
    )
);

define ("URL_SUFFIXE_ADMIN", "B6BEZ5FCU4YCBY8UB9ZHD92F544CKF");
define ("URL_SUFFIXE_GENERATE", "U4BZ9BZ5BK9JVCBY4YU4U6FLH2L2Y8");
define ("SALT", "8j5BnPSJS?nPbaD*nz+mLctc*5q=2K+76&zU_mF^4H5X+fukFAPqVtUGkhsxJwwuyxB6VVATeah%yaZRmmb=Vg&y#vs9tmdBDnsr");
define ("REGEX_VALID_WORDS", "/^[A-ZÀÁÂÃÄÅÇÑÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜÝ]?[a-zñçàáâãäåçèéêëìíîïðòóôõöøùúûüýÿ\-']*([\s]?[A-ZÀÁÂÃÄÅÇÑÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜÝ]?[a-zñçàáâãäåçèéêëìíîïðòóôõöøùúûüýÿ\-']*)*$/u");
