<?php

namespace AppBundle\Entity;

use AppBundle\Form\Dto\LogSearch;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * LogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @var array
     */
    protected $rootUsers;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param string $filePath
     * @return int
     */
    public function getLastStamp($filePath)
    {
        $lastLog = $this->findOneBy(['filePath' => $filePath], ['stamp' => 'DESC']);

        return $lastLog ? $lastLog->getStamp() : 0;
    }

    /**
     * @param LogSearch $logSearch
     * @return \Doctrine\ORM\Query
     */
    public function getLogsSearchQuery(LogSearch $logSearch)
    {
        $qb = $this->getQueryBuilder();

        if ($logSearch->search) {
            if (true === $logSearch->isRegExp) {
                $qb->andWhere('REGEXP(l.request, :search) = true');
            } else {
                $qb->andWhere('l.request LIKE :search');
            }

            $qb->setParameter('search', '%'.$logSearch->search.'%');
        }

        if (!empty($logSearch->files)) {
            $qb
                ->andWhere('l.filePath IN (:files)')
                ->setParameter('files', $logSearch->files);
        }

        if (!empty($logSearch->users)) {
            $qb
                ->andWhere('l.username IN (:users)')
                ->setParameter('users', $logSearch->users);
        }

        if (!empty($logSearch->timeIntervals)) {
            $orX = $qb->expr()->orX();

            foreach ($logSearch->timeIntervals as $dateTimeInterval) {
                $orX->add($qb->expr()->between('l.stamp', $dateTimeInterval->from->getTimestamp(), $dateTimeInterval->to->getTimestamp()));
            }

            $qb->andWhere($orX);
        }

        return $qb->getQuery();
    }

    /**
     * @return array
     */
    public function getUserLogFiles()
    {
        $qb = $this->getQueryBuilder()->groupBy('l.filePath');

        $logs = $qb->getQuery()->getResult();

        return array_map(function($l) { return $l->getFilePath(); }, $logs);
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $qb = $this->getQueryBuilder()->groupBy('l.username');

        $users = $qb->getQuery()->getResult();

        return array_map(function($l) { return $l->getUsername(); }, $users);
    }

    /**
     * @param array $rootUsers
     */
    public function setRootUsers(array $rootUsers)
    {
        $this->rootUsers = $rootUsers;
    }

    /**
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('l')
            ->from($this->_entityName, 'l')
        ;
        $this->filterByUsername($qb);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     */
    protected function filterByUsername(QueryBuilder $qb)
    {
        $username = $this->tokenStorage->getToken()->getUser()->getUsername();

        if (false === in_array($username, $this->rootUsers)) {
            $qb->where('l.username = :username')
                ->setParameter('username', $username);
        }
    }
}
