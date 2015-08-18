<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class Age extends Constraint
{
    public $minMessage = 'Age must be {{ min }} years or older. {{ age }} given.';
    public $min;

    /**
     * @param $options
     *
     * @throws MissingOptionsException
     */
    public function __construct($options = null)
    {
        if (null !== $options && !is_array($options)) {
            $options = ['min' => $options];
        }

        parent::__construct($options);

        if (null === $this->min) {
            throw new MissingOptionsException(sprintf(
                'Option "min" must be given for constraint %s',
                __CLASS__
            ), ['min']);
        }
    }
}
