<?php

namespace AppBundle\Service;

use AppBundle\DTO\ProjectDTO;
use AppBundle\DTO\ProjectOverviewDTO;
use AppBundle\Entity\Project;
use AppBundle\Exception\ProjectNotFoundException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProjectService
 * @package AppBundle\Service
 */
class ProjectService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ProjectRepository
     */
    private $projectRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $this->entityManager->getRepository('AppBundle:Project');
        $this->validator = $validator;
    }

    /**
     * @param $userId
     * @return array
     */
    public function getProjectsByUser($userId)
    {
        $projects = $this->projectRepository->getProjectsByUser($userId);
        return array_map(
            function ($project) {
                return ProjectDTO::withEntity($project);
            },
            $projects
        );
    }

    /**
     * @param $userId
     * @param $projectId
     * @return ProjectDTO
     * @throws ProjectNotFoundException
     */
    public function getProjectByUser($userId, $projectId)
    {
        $project = $this->projectRepository->getProjectWithWorkTime($projectId, $userId);
        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        return ProjectDTO::withEntity($project, true);
    }

    /**
     * @param $userId
     * @param $title
     * @param null|string $description
     * @param null|float $hourlyRate
     * @param null|int $id
     * @return Project
     *
     * @throws ProjectNotFoundException
     * @throws UserNotFoundException
     * @throws ValidationException
     */
    public function saveProject($userId, $title, $description = null, $hourlyRate = null, $id = null)
    {
        if ($id !== null) {
            $project = $this->projectRepository->getProject($id, $userId);
            if ($project === null) {
                throw new ProjectNotFoundException();
            }
        } else {
            $project = new Project();
        }

        $user = $this->entityManager->getRepository('AppBundle:User')->find($userId);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        $project->setTitle($title);
        $project->setDescription($description);
        $project->setHourlyRate($hourlyRate);
        $project->setUser($user);

        $errors = $this->validator->validate($project);
        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $this->projectRepository->saveProject($project);
        return ProjectDTO::withEntity($project);
    }

    /**
     * @param $userId
     * @param $projectId
     * @return ProjectOverviewDTO
     * @throws ProjectNotFoundException
     */
    public function getProjectOverview($userId, $projectId)
    {
        $project = $this->projectRepository->getProjectWithWorkTime($projectId, $userId);
        if ($project === null) {
            throw new ProjectNotFoundException();
        }
        $projectOverviewDTO = new ProjectOverviewDTO();
        $projectOverviewDTO->setProject(ProjectDTO::withEntity($project, true));
        $totalDurationInMinutes = 0;
        foreach ($project->getWorkTimes() as $workTime) {
            if ($workTime->getEndDate() === null) {
                continue;
            }
            $totalDurationInMinutes += ($workTime->getDuration()->format('H') * 60) +
                $workTime->getDuration()->format('i');
        }

        $projectOverviewDTO->setAmountWorkTimes(count($project->getWorkTimes()));
        $minutes = ($totalDurationInMinutes%60);
        $projectOverviewDTO->setTotalDuration(
            floor($totalDurationInMinutes / 60) .':'. ($minutes >= 10 ? $minutes : $minutes.'0')
        );
        #$projectOverviewDTO->setTotalDurationByMonth()
        #$projectOverviewDTO->setTotalIncome()
        #$projectOverviewDTO->setTotalIncomeByMonth()
        return $projectOverviewDTO;
    }
}