<?php

namespace AppBundle\DTO;

use AppBundle\Entity\WorkTime;

/**
 * Class WorkTimeDTO
 * @package AppBundle\DTO
 */
class WorkTimeDTO
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    private $duration;

    /**
     * @var string
     */
    private $description;

    /**
     * @var ProjectDTO
     */
    private $project;

    /**
     * @param WorkTime $workTime
     * @return WorkTimeDTO
     */
    public static function withEntity(WorkTime $workTime)
    {
        $dto = new WorkTimeDTO();
        $dto->setId($workTime->getId());
        $dto->setDescription($workTime->getDescription());
        $dto->setDuration($workTime->getDuration() ? $workTime->getDuration()->format('H:i') : null);
        $dto->setStartDate($workTime->getStartDate());
        $dto->setEndDate($workTime->getEndDate());
        $dto->setProject(ProjectDTO::withEntity($workTime->getProject()));
        return $dto;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}