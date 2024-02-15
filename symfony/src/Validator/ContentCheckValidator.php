<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContentCheckValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var ContentCheck $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (str_contains(strtolower($value), 'fuck')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
