<?php

namespace AppBundle\Service;

use AppBundle\DTO\WorkTimeDTO;
use AppBundle\Entity\WorkTime;
use AppBundle\Exception\WorkTimeNotActiveException;
use AppBundle\Exception\WorkTimeNotFoundException;
use AppBundle\Exception\ProjectNotFoundException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Exception\ValidationException;
use AppBundle\Exception\WorkTimeNotStoppedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class WorkTimeService
 * @package AppBundle\Service
 */
class WorkTimeService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\WorkTimeRepository
     */
    private $workTimeRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->workTimeRepository = $entityManager->getRepository('AppBundle:WorkTime');
    }

    /**
     * @param $userId
     * @param $projectId
     * @return WorkTimeDTO
     * @throws ProjectNotFoundException
     * @throws UserNotFoundException
     * @throws WorkTimeNotStoppedException
     */
    public function startWorkTime($userId, $projectId)
    {
        $workTime = $this->workTimeRepository->findActiveWorkTime($userId);
        if ($workTime !== null) {
            throw new WorkTimeNotStoppedException(WorkTimeDTO::withEntity($workTime));
        }

        $project = $this->entityManager->getRepository('AppBundle:Project')
            ->getProject($projectId, $userId);

        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        $user = $this->entityManager->getRepository('AppBundle:User')
            ->find($userId);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        $workTime = $this->workTimeRepository->startWorkTime($user, $project);
        return WorkTimeDTO::withEntity($workTime);
    }

    /**
     * @param $userId
     * @param $workTimeId
     * @param $endDateRequest
     * @param $durationRequest
     * @param $descriptionRequest
     * @return WorkTimeDTO
     * @throws ValidationException
     * @throws WorkTimeNotActiveException
     * @throws WorkTimeNotFoundException
     */
    public function stopWorkTime($userId, $workTimeId, $endDateRequest, $durationRequest, $descriptionRequest)
    {
        $workTime = $this->workTimeRepository->findWorkTimeByUserAndId($userId, $workTimeId);
        if ($workTime === null) {
            throw new WorkTimeNotFoundException();
        }

        if ($workTime->getEndDate() !== null) {
            throw new WorkTimeNotActiveException();
        }

        $validations = array(
            array($endDateRequest, array(new NotNull(), new DateTime()), 'endDate'),
            array($durationRequest, array(
                new NotNull(),
                new Regex(array('value' => '/([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/'))),
                'duration'
            ),
            array($descriptionRequest, array(new Length(array('min' => 5, 'max' => '255'))))
        );

        $validationErrors = new ConstraintViolationList();
        foreach ($validations as $values) {
            $errors = $this->validator->validate($values[0], $values[1]);
            if ($errors->count() > 0) {
                foreach ($errors as $error) {
                    /* @var $error ConstraintViolationInterface */
                    $validationErrors->add(
                        new ConstraintViolation(
                            $error->getMessage(),
                            $error->getMessageTemplate(),
                            $error->getMessageParameters(),
                            $error->getRoot(),
                            $values[2],
                            $error->getInvalidValue()
                        )
                    );
                }
            }
        }

        if ($validationErrors->count() > 0) {
            throw new ValidationException($validationErrors);
        }

        $duration = new \DateTime();
        $durationRequest = explode(':', $durationRequest);
        $duration->setTime($durationRequest[0], $durationRequest[1], 0);
        $endDate = new \DateTime($endDateRequest);
        $this->workTimeRepository->stopWorkTime($workTime, $endDate, $duration, $descriptionRequest);
        return WorkTimeDTO::withEntity($workTime);
    }

    /**
     * @param $userId
     * @param $workTimeId
     * @return WorkTimeDTO
     * @throws WorkTimeNotFoundException
     */
    public function findWorkTimeByUserAndId($userId, $workTimeId)
    {
        $workTime = $this->workTimeRepository->findWorkTimeByUserAndId($userId, $workTimeId);
        if ($workTime === null) {
            throw new WorkTimeNotFoundException();
        }

        return WorkTimeDTO::withEntity($workTime);
    }
}