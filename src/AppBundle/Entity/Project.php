<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @UniqueEntity(fields={"title", "user"})
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @NotBlank()
     * @Length(min="3", max="255")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Length(min="3", max="255")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="hourlyRate", type="decimal", precision=12, scale=3, nullable=true)
     */
    private $hourlyRate;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var WorkTime[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkTime", mappedBy="project")
     */
    private $workTimes;

    public function __construct()
    {
        $this->workTimes = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hourlyRate
     *
     * @param float $hourlyRate
     * @return Project
     */
    public function setHourlyRate($hourlyRate)
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    /**
     * Get hourlyRate
     *
     * @return float
     */
    public function getHourlyRate()
    {
        return $this->hourlyRate;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return WorkTime[]
     */
    public function getWorkTimes()
    {
        return $this->workTimes;
    }

    /**
     * @param WorkTime[] $workTimes
     */
    public function setWorkTimes($workTimes)
    {
        $this->workTimes = $workTimes;
    }
}
