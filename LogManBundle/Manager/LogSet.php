<?php

namespace LogManBundle\Manager;

use Doctrine\ORM\EntityManager;
use LogManBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Session\Session;
use LogManBundle\Repository\LogRepository;

/**
 * Description of Log
 *
 * @author ManaraR
 */
class LogSet {

    /**
     *
     * @var Doctrine\ORM\EntityManager 
     */
    var $em;
    var $session;
    var $sessionId;

    /**
     * 
     * @param EntityManager $em
     * @param Session $session
     */
    public function __construct(EntityManager $em, Session $session) {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * 
     * @param type $text
     * @param type $details
     */
    public function log($text, $details) {
        $this->dataRecord("debug", $text, $details);
    }

    /**
     * 
     * @param type $text
     * @param type $details
     */
    public function warning($text, $details) {
        $this->dataRecord("warning", $text, $details);
    }

    /**
     * 
     * @param type $text
     * @param type $details
     */
    public function error($text, $details) {
        $this->dataRecord("critical", $text, $details);
    }

    /**
     * 
     * @param type $status
     * @param type $text
     * @param type $details
     */
    private function dataRecord($status, $text, $details) {

        $objDateTime = new \DateTime('NOW');

        $log = new Log();
        $log->setDateTime($objDateTime);
        $log->setLog($text);
        $log->setStatus($status);
        $log->setSessionId($this->getSessionId());
        $log->setDetail($details);

        $this->em->persist($log);
        $this->em->flush();
    }

    /**
     * 
     * @param type $status
     * @param type $text
     * @param type $details
     */
    public function customDataRecord($id , $date, $status, $text, $details) {

        $log = new Log();

        $objDateTime = new \DateTime($date);

        $log->setDateTime($objDateTime);
        $log->setLog($text);
        $log->setStatus($status);
        $log->setSessionId($id);
        $log->setDetail($details);

        $this->em->persist($log);
        $this->em->flush();
    }

    /**
     * 
     */
    public function setSession() {

        $sessionId = null;
        if ($this->session->isStarted()) {
            $sessionId = $this->session->getId();
        } else {
            $this->session->start();
            $sessionId = $this->session->getId();
        }

        $this->sessionId = $sessionId;
    }

    /**
     * 
     * @return type
     */
    public function getSessionId() {
        return $this->sessionId;
    }

}