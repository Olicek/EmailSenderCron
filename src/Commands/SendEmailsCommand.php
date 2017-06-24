<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Commands;

use Oli\EmailSender\Cron\IMailer;
use Oli\EmailSender\Persistence\IPersistEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TestCommand
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron\Commands
 */
final class SendEmailsCommand extends Command
{

	/**
	 * @var IMailer
	 */
	private $mailer;

	/**
	 * @var IPersistEmail
	 */
	private $persistEmail;

	public function __construct(IMailer $mailer, IPersistEmail $persistEmail, $name = null)
	{
		parent::__construct($name);
		$this->mailer = $mailer;
		$this->persistEmail = $persistEmail;
	}

	/**
	 * In this method setup command, description and its parameters
	 */
	protected function configure()
	{
		$this->setName('emails:send');
		$this->setDescription('Sends emails.');
		$this->addArgument('number', InputArgument::OPTIONAL, 'Number of emails to send.');
	}

	/**
	 * Here all logic happens
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null
	 * @throws InvalidArgumentException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$number = $input->getArgument('number');

		$output->writeln(sprintf('Start sending'));
		$emails = $this->persistEmail->loadEmails($number);
		$progress = new ProgressBar($output, count($emails));

		$progress->display();
		foreach ($emails as $email)
		{
			$this->mailer->send($email);
			$progress->advance();
		}

		$progress->finish();
		$output->writeln(sprintf('Your emails was sent'));

		return 0;
	}

}
