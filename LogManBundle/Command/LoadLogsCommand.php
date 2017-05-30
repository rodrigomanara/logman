<?php

namespace LogManBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of LoadLogs
 *
 * @author ManaraR
 */
class LoadLogsCommand extends ContainerAwareCommand {

    var $logmanager, $logFrom, $env, $dir, $output, $input, $io;

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);

        $this->logmanager = $this->getContainer()->get('logman.service');
        $this->env = $this->getContainer()->getParameter("kernel.environment");
        $this->dir = $this->getContainer()->getParameter("kernel.logs_dir");
        $this->input = $input;
        $this->output = $output;


        $this->io = new SymfonyStyle($this->input, $this->output);
    }

    /**
     * 
     */
    protected function configure() {
        $this
                ->setName('logman:load:logs')
                ->setDescription('Load system logs and also any logs that are availible on the app folder')
                ->addOption('local', 'loc', InputOption::VALUE_OPTIONAL, 'Specify the local/server you runinng the command from', null)

        ;
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->logfrom = $input->getOption('local');
        $this->findLogs();
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function findLogs() {

        $finder = new Finder();

        $this->io->block("start search");
        $files = $finder->files()->in($this->dir)->name($this->env . ".log");

        $fileSearch = array();
        foreach ($files as $file) {
            $fileSearch[] = $file->getRealPath();
        }
        $this->io->block("End search");

        ##start process
        $this->io->comment("start command process logman");
        foreach ($fileSearch as $file) {
            $this->io->block("_______");
            $this->recordLogs($this->readLogs($file) , $file);
            
        }
        $this->io->comment("end command process logman");
    }

    /**
     * 
     * @param OutputInterface $output
     * @param type $file_path
     */
    protected function readLogs($file_path) {


        $this->io->comment("Start Reading file Content", true);

        $handler = fopen($file_path, 'r');
        $build = array();
        if ($handler) {
            while (($line = fgets($handler)) !== false) {
                array_push($build, $this->buildRow($line));
            }
        }
        $this->io->comment("End Reading file Content", true);
        return $build;
    }

    /**
     * 
     * @param OutputInterface $output
     * @param array $build
     */
    protected function buildRow($build) {

        preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $build, $date);
        preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{2}/", $build, $time);

        $pattern = "/(INFO:|CRITICAL:|DEBUG:|ERROR:|ALERT:WARNING:)/";
        $str = preg_split($pattern, $build);

        preg_match($pattern, $build, $output);
        $status = end($output);

        $text = '';
        if (end($str) !== null) {
            $text = end($str);
        }

        return array(
            'date' => end($date),
            'time' => end($time),
            'id' => $this->env . 'log file',
            'status' => str_replace(":", "", strtolower($status)),
            'details' => $text,
        );
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $datas
     * @param type $filename
     * @return type
     */
    private function recordLogs(array $datas = array(), $filename) {

        //clear existing logs
        $this->clearLogs($filename);

        // start recording array
        $this->io->comment("start recording logs");
        foreach ($datas as $data) {
            $date = $data['date'] . " " . $data['time'];
            $status = $data['status'];
            $details = $data['details'];
            $this->logmanager->customDataRecord("file_logs", $date, $status, $this->env, $details);
        }
        $this->io->comment("end record");
    }

    /**
     * 
     * @param type $input
     * @param type $output
     * @param type $filename
     */
    private function clearLogs($filename) {

        $this->fs = new Filesystem();

        $this->io->comment(sprintf('Clearing the logs for the <info>%s</info> environment', $this->env));
        $this->fs->remove($filename);
        if (!$this->fs->exists($filename)) {
            $this->io->success(sprintf('Logs for the "%s" environment was successfully cleared.', $this->env));
        } else {
            $this->io->error(sprintf('Logs for the "%s" environment could not be cleared.', $this->env));
        }
    }

}
