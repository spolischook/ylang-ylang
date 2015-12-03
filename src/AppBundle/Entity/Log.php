<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log.
 *
 * @ORM\Table(indexes={
 *  @ORM\Index(name="user", columns={"username"}),
 *  @ORM\Index(name="stamp", columns={"stamp"}),
 *  @ORM\Index(name="filePath", columns={"filePath"}),
 *  @ORM\Index(name="request", columns={"request"}),
 *  @ORM\Index(name="stamp_request", columns={"stamp", "request"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LogRepository")
 */
class Log
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var string
     *
     * @ORM\Column(name="logname", type="string", length=255)
     */
    private $logname;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="stamp", type="integer")
     */
    private $stamp;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=255)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="request", type="string", length=8192)
     */
    private $request;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="responseBytes", type="integer")
     */
    private $responseBytes;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="filePath", type="string", length=1024)
     */
    private $filePath;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set host.
     *
     * @param string $host
     *
     * @return Log
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set logname.
     *
     * @param string $logname
     *
     * @return Log
     */
    public function setLogname($logname)
    {
        $this->logname = $logname;

        return $this;
    }

    /**
     * Get logname.
     *
     * @return string
     */
    public function getLogname()
    {
        return $this->logname;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return Log
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set stamp.
     *
     * @param int $stamp
     *
     * @return Log
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get stamp.
     *
     * @return int
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * Set time.
     *
     * @param string $time
     *
     * @return Log
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time.
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set request.
     *
     * @param string $request
     *
     * @return Log
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request.
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Log
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set responseBytes.
     *
     * @param int $responseBytes
     *
     * @return Log
     */
    public function setResponseBytes($responseBytes)
    {
        $this->responseBytes = $responseBytes;

        return $this;
    }

    /**
     * Get responseBytes.
     *
     * @return int
     */
    public function getResponseBytes()
    {
        return $this->responseBytes;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return Log
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     *
     * @return Log
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }
}
