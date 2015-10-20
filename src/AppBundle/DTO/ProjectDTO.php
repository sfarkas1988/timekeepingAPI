<?php

namespace AppBundle\DTO;

use AppBundle\Entity\Project;

/**
 * Class ProjectDTO
 * @package AppBundle\DTO
 */
class ProjectDTO
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $hourlyRate;

    /**
     * @var WorkTimeDTO[]
     */
    private $workTimes = array();

    /**
     * @param Project $project
     * @param bool|false $withWorkTime
     * @return ProjectDTO
     */
    public static function withEntity(Project $project, $withWorkTime = false)
    {
        $dto = new ProjectDTO();
        $dto->setId($project->getId());
        $dto->setDescription($project->getDescription());
        $dto->setHourlyRate($project->getHourlyRate());
        $dto->setTitle($project->getTitle());

        if ($withWorkTime) {
            foreach ($project->getWorkTimes() as $workTime) {
                $dto->addWorkTime(WorkTimeDTO::withEntity($workTime));
            }
        }

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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return string
     */
    public function getHourlyRate()
    {
        return $this->hourlyRate;
    }

    /**
     * @param string $hourlyRate
     */
    public function setHourlyRate($hourlyRate)
    {
        $this->hourlyRate = $hourlyRate;
    }

    /**
     * @return WorkTimeDTO[]
     */
    public function getWorkTimes()
    {
        return $this->workTimes;
    }

    /**
     * @param WorkTimeDTO[] $workTimes
     */
    public function setWorkTimes($workTimes)
    {
        $this->workTimes = $workTimes;
    }

    /**
     * @param WorkTimeDTO $workTimeDTO
     */
    public function addWorkTime(WorkTimeDTO $workTimeDTO)
    {
        $this->workTimes[] = $workTimeDTO;
    }



}