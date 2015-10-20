<?php

namespace AppBundle\DTO;

/**
 * Class ProjectOverviewDTO
 * @package AppBundle\DTO
 */
class ProjectOverviewDTO
{
    /**
     * @var int
     */
    private $project;

    /**
     * @var int
     */
    private $amountWorkTimes;

    /**
     * @var float
     */
    private $totalDuration;

    /**
     * @var array
     */
    private $totalDurationByMonth = array();

    /**
     * @var float
     */
    private $totalIncome;

    /**
     * @var array
     */
    private $totalIncomeByMonth = array();

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

    /**
     * @return mixed
     */
    public function getAmountWorkTimes()
    {
        return $this->amountWorkTimes;
    }

    /**
     * @param mixed $amountWorkTimes
     */
    public function setAmountWorkTimes($amountWorkTimes)
    {
        $this->amountWorkTimes = $amountWorkTimes;
    }

    /**
     * @return mixed
     */
    public function getTotalDuration()
    {
        return $this->totalDuration;
    }

    /**
     * @param mixed $totalDuration
     */
    public function setTotalDuration($totalDuration)
    {
        $this->totalDuration = $totalDuration;
    }

    /**
     * @return array
     */
    public function getTotalDurationByMonth()
    {
        return $this->totalDurationByMonth;
    }

    /**
     * @param array $totalDurationByMonth
     */
    public function setTotalDurationByMonth($totalDurationByMonth)
    {
        $this->totalDurationByMonth = $totalDurationByMonth;
    }

    /**
     * @return mixed
     */
    public function getTotalIncome()
    {
        return $this->totalIncome;
    }

    /**
     * @param mixed $totalIncome
     */
    public function setTotalIncome($totalIncome)
    {
        $this->totalIncome = $totalIncome;
    }

    /**
     * @return array
     */
    public function getTotalIncomeByMonth()
    {
        return $this->totalIncomeByMonth;
    }

    /**
     * @param array $totalIncomeByMonth
     */
    public function setTotalIncomeByMonth($totalIncomeByMonth)
    {
        $this->totalIncomeByMonth = $totalIncomeByMonth;
    }

}