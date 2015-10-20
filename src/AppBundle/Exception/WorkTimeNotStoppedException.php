<?php

namespace AppBundle\Exception;

use AppBundle\DTO\WorkTimeDTO;

/**
 * Class WorkTimeNotStoppedException
 * @package AppBundle\Exception
 */
class WorkTimeNotStoppedException extends \Exception
{
    /**
     * @var WorkTimeDTO
     */
    private $workTimeDTO;

    /**
     * @param WorkTimeDTO $workTimeDTO
     */
    public function __construct(WorkTimeDTO $workTimeDTO)
    {
        $this->workTimeDTO = $workTimeDTO;
    }

    /**
     * @return WorkTimeDTO
     */
    public function getWorkTimeDTO()
    {
        return $this->workTimeDTO;
    }

    /**
     * @param WorkTimeDTO $workTimeDTO
     */
    public function setWorkTimeDTO($workTimeDTO)
    {
        $this->workTimeDTO = $workTimeDTO;
    }
}