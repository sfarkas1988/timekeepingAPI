<?php

namespace AppBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationException
 * @package AppBundle\Exception
 */
class ValidationException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $constraintViolationList;

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     */
    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $this->constraintViolationList = $constraintViolationList;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getConstraintViolationList()
    {
        return $this->constraintViolationList;
    }
}