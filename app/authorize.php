<?php

namespace Vendor\Package;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use SymfonyComponentHttpFoundationRequest;
use SymfonyComponentHttpFoundationResponse;


class Validator
{
  public static function validateAge($object, ExecutionContextInterface $context)
  {
    $dob = new \DateTime($object);
    $now = new \DateTime();

    $diff = $now->diff($dob);
    if($diff->y < 18) {
      $context->buildViolation('Vous devez être majeur pour participer.')
          ->atPath('birthdate')
          ->addViolation()
      ;
    }
  }
}
