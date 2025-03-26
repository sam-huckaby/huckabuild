<?php

namespace Foundry\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class NewCommand extends Command
{
    protected static $defaultName = 'new';

    protected function configure()
    {
        $this
            ->setDescription('Create a new Foundry application')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectName = $input->getArgument('name');
        $fs = new Filesystem();

        // Create project directory
        if ($fs->exists($projectName)) {
            $output->writeln("<error>Directory {$projectName} already exists!</error>");
            return Command::FAILURE;
        }

        $output->writeln("<info>Creating new Foundry application in {$projectName}...</info>");
        
        // Clone the Foundry repository
        $process = new Process(['git', 'clone', 'https://github.com/sam-huckaby/foundry.git', $projectName]);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln('<error>Failed to clone the repository</error>');
            return Command::FAILURE;
        }

        // Remove .git directory
        $fs->remove($projectName . '/.git');

        // Create SQLite database
        $dbPath = $projectName . '/database/foundry.sqlite';
        $fs->touch($dbPath);

        // Run composer install
        $process = new Process(['composer', 'install'], $projectName);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln('<error>Failed to install dependencies</error>');
            return Command::FAILURE;
        }

        // Copy .env.example to .env
        $fs->copy($projectName . '/.env.example', $projectName . '/.env');

        $output->writeln("<info>Foundry application created successfully!</info>");
        $output->writeln("<info>Next steps:</info>");
        $output->writeln("  1. cd {$projectName}");
        $output->writeln("  2. php foundry serve");
        $output->writeln("  3. Visit http://localhost:8000");

        return Command::SUCCESS;
    }
} 