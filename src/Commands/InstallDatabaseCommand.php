<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Commands;

use Nette\Configurator;
use Oli\EmailSender\Persistence\Adapters\IDatabaseAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class InstallDatabaseCommand
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron\Commands
 */
class InstallDatabaseCommand extends Command
{

	/**
	 * In this method setup command, description and its parameters
	 * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
	 */
	protected function configure()
	{
		$this->setName('emails:install');
		$this->setDescription('Instals database for cron .');
		$this->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Path to project configuration file');
	}

	/**
	 * Here all logic happens
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null
	 * @throws \Nette\DI\MissingServiceException
	 * @throws \Nette\InvalidArgumentException
	 * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$projectConfigFile = $input->getOption('configuration');

		$io = new SymfonyStyle($input, $output);
		$io->title('Install cron Email sender');

		$rootDir = __DIR__ . '/../..';
		$tmpDir = $rootDir . '/temp';
		$confDir = $rootDir . '/src/Config';

		$configurator = new Configurator();
		$configurator->defaultExtensions = [];
		$configurator->setDebugMode(true);
		$configurator->setTempDirectory($tmpDir);

		$configFiles = [$confDir . '/config.neon'];

		if($projectConfigFile !== null)
		{
			if(!is_file($projectConfigFile))
			{
				$output->writeln(sprintf('Project config file at path %s does not exist.', $projectConfigFile));

				return 1;
			}

			$configFiles[] = $projectConfigFile;
		}

		foreach ($configFiles as $configFile)
		{
			$configurator->addConfig($configFile);
		}

		$parameters = [
			'rootDir' => $rootDir,
			'tmpDir' => $tmpDir,
		];

		$configurator->addParameters($parameters);
		$container = $configurator->createContainer();

		/** @var IDatabaseAdapter $adapter */
		$adapter = $container->getByType(IDatabaseAdapter::class);
		$io->text('Database is preparing...');
		$adapter->install();

		$io->success('Database was prepared.');

		return 0;
	}

}
