<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * WorkTimeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkTimeRepository extends EntityRepository
{

    /**
     * @param $userId
     * @return null|WorkTime
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findActiveWorkTime($userId)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT
              w, p
            FROM AppBundle:WorkTime w
            JOIN w.project p
            WHERE
              w.user=:userId AND
              w.endDate is NULL
        ');

        $query->setParameter('userId', $userId);
        return $query->getOneOrNullResult();
    }

    /**
     * @param $userId
     * @param $workTimeId
     * @return WorkTime|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findWorkTimeByUserAndId($userId, $workTimeId)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT
               w
            FROM AppBundle:WorkTime w
            WHERE
              w.user=:userId AND
              w.id=:workTimeId
        ');

        $query->setParameter('userId', $userId);
        $query->setParameter('workTimeId', $workTimeId);
        return $query->getOneOrNullResult();
    }

    /**
     * @param UserInterface $user
     * @param Project $project
     * @return WorkTime
     */
    public function startWorkTime(UserInterface $user, Project $project)
    {
        $workTime = new WorkTime();
        $workTime->setStartDate(new \DateTime());
        $workTime->setUser($user);
        $workTime->setProject($project);
        $this->getEntityManager()->persist($workTime);
        $this->getEntityManager()->flush();
        return $workTime;
    }

    /**
     * @param WorkTime $workTime
     * @param \DateTime $endDate
     * @param \DateTime $duration
     * @param string $description
     */
    public function stopWorkTime(WorkTime $workTime, \DateTime $endDate, \DateTime $duration, $description)
    {
        $workTime->setEndDate($endDate);
        $workTime->setDuration($duration);
        $workTime->setDescription($description);
        $this->getEntityManager()->merge($workTime);
        $this->getEntityManager()->flush();
    }
}
