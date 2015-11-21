<?php

namespace AppBundle\Command;

use AppBundle\Parser\LogParser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportLogsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import-logs')
            ->setDescription('Import user logs')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Unix username for import logs files from home directory'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $parser = new LogParser();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($this->getUserLogFiles($username) as $file) {
            $handle = @fopen($file, "r");
            $lastLogStamp = $em->getRepository('AppBundle:Log')->getLastStamp($file);

            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $logEntity = $parser->parseLog($line);

                    if ($lastLogStamp >= $logEntity->getStamp()) {
                        continue;
                    }

                    $logEntity
                        ->setUsername($username)
                        ->setFilePath($file);

                    $em->persist($logEntity);
                }
                if (!feof($handle)) {
                    throw new \Exception("Unexpected end of file");
                }
                fclose($handle);
            }
        }

        $em->flush();
    }

    /**
     * @param string $username
     * @return array
     * @throws \Exception
     */
    protected function getUserLogFiles($username)
    {
        $dir = '/home/'.$username.'/logs';

        if (false === scandir($dir)) {
            throw new \Exception(sprintf('"%s" directory does not exists'));
        }

        return $this->getFiles($dir);
    }

    /**
     * @param $dir
     * @param array $files
     * @return array
     */
    protected function getFiles($dir, array &$files = [])
    {
        foreach (scandir($dir) as $file) {
            if (in_array($file, [".",".."])) {
                continue;
            }

            $fullPath = $dir.'/'.$file;

            if (is_dir($fullPath)) {
                $this->getFiles($fullPath, $files);
            } else {
                $files[] = $fullPath;
            }
        }

        return $files;
    }
}
