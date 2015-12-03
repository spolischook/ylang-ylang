<?php

namespace AppBundle\Command;

use AppBundle\Parser\LogParser;
use Psr\Log\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

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
        $repository = $em->getRepository('AppBundle:Log');
        $batchSize = 20;

        $logDir = $this->getUserLogDir($username);

        if (false === is_dir($logDir)) {
            throw new InvalidArgumentException(sprintf('"%s" directory does not exists', $logDir));
        }

        $finder = new Finder();
        $finder->files()->in($logDir);

        foreach ($finder as $file) {
            $output->writeln(sprintf('<info>Importing "%s"</info>', $file));
            $lastLogStamp = $repository->getLastStamp($file);
            $logs = $parser->parseFile($file, $username, $lastLogStamp);

            foreach ($logs as $key => $log) {
                $em->persist($log);

                if (($key % $batchSize) === 0) {
                    $em->flush();
                    $em->clear();
                }
            }

            $em->flush();
            $em->clear();
        }
    }

    /**
     * @param string $username
     * @throw UsernameNotFoundException
     * @return string
     */
    protected function getUserLogDir($username)
    {
        if (false === $userinfo = posix_getpwnam($username)) {
            throw new UsernameNotFoundException();
        }

        $homeDir = $userinfo['dir'];

        return $homeDir.'/logs';
    }
}
