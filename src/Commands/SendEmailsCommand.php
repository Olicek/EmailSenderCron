<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Commands;

use Nette\Configurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class TestCommand
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron\Commands
 */
final class SendEmailsCommand extends Command
{

	/**
	 * In this method setup command, description and its parameters
	 */
	protected function configure()
	{
		$this->setName('emails:send');
		$this->setDescription('Sends emails.');
		$this->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Path to project configuration file');
		$this->addArgument('number', InputArgument::OPTIONAL, 'Number of emails to send.');
	}

	/**
	 * Here all logic happens
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null
	 * @throws InvalidArgumentException
	 * @throws \Nette\DI\MissingServiceException
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$number = $input->getArgument('number');
		$projectConfigFile = $input->getOption('configuration');

		$io = new SymfonyStyle($input, $output);
		$io->title('Cron mailer');

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

		/** @var SendEmailsApplication $sendEmailsApplication */
		$sendEmailsApplication = $container->getByType(SendEmailsApplication::class);
		list($successfulEmails, $unsuccessfulEmails) = $sendEmailsApplication->send($number, $io);

		$io->text('Number of successfully sent emails: ' . $successfulEmails);
		if ($unsuccessfulEmails)
		{
			if (!$successfulEmails)
			{
				$io->text('Number of unsuccessfully sent emails: ' . $unsuccessfulEmails);
				$io->warning('None of emails was sent.');
				return 1;
			}
			$io->text('Number of unsuccessfully sent emails: ' . $unsuccessfulEmails);
			$io->warning('Some emails was sent, but not all of them.');
		}
		else
		{
			$io->success('Your emails was sent');
		}

		return 0;
	}

}
