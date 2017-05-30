<?php

namespace LogManBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction() {
        
            
        
        return $this->render('LogManBundle:Default:index.html.twig');
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function getLogsAction(Request $request) {


        $log = $this->get('logman.service');

        $case = rand(1, 2);

        switch ($case) {
            case 1 : $log->log("page start", "error");
                
                sleep(5);
                ;
                break;
            case 2 : $log->warning("page start", "test me");
                sleep(5);
                break;
        }
        switch ($case) {
            case 1 : $log->log("page end", "error");
                
                sleep(5);
                ;
                break;
            case 2 : $log->warning("page end", "test me");
                sleep(5);
                break;
        }

        return $this->render('LogManBundle:Default:report.html.twig');
    }

    public function getReportAction() {


        $em = $this->getDoctrine()->getManager();
        $return = $em->getRepository("LogManBundle:Log");

        $dateA = "2017-01-01";
        $dateB = "2017-10-10";

        $data = $return->getTotalByDate($dateA, $dateB);

            
        return $this->render('LogManBundle:Default:report.html.twig', array('data' => $data));
    }

    
            
    
}
