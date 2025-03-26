<?php

namespace Huckabuild\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';

    protected function configure()
    {
        $this
            ->setDescription('Serve the Huckabuild application in development mode')
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'The port to serve the application on', 8000)
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host to serve the application on', 'localhost');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $port = $input->getOption('port');
        $host = $input->getOption('host');

        $output->writeln("<info>Starting Huckabuild development server...</info>");
        $output->writeln("<info>Server running on http://{$host}:{$port}</info>");
        $output->writeln("<info>Press Ctrl+C to stop the server</info>");

        $process = new Process(['php', '-S', "{$host}:{$port}", '-t', 'public']);
        $process->setWorkingDirectory(getcwd());
        $process->setTty(true);
        $process->run();

        return Command::SUCCESS;
    }
} 