<?php

namespace LogManBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="LogManBundle\Repository\LogRepository")
 */
class Log {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="session_id", type="string", length=60)
     */
    private $sessionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="log", type="string", length=255)
     */
    private $log;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text")
     */
    private $detail;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set sessionId
     *
     * @param integer $sessionId
     *
     * @return Log
     */
    public function setSessionId($sessionId) {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return int
     */
    public function getSessionId() {
        return $this->sessionId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Log
     */
    public function setDateTime($date) {
        $this->datetime = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Log
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set log
     *
     * @param string $log
     *
     * @return Log
     */
    public function setLog($log) {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return Log
     */
    public function setDetail($detail) {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail() {
        return $this->detail;
    }

}
