<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Commands;
use Oli\EmailSender\Cron\IMailer;
use Oli\EmailSender\Persistence\IPersistEmail;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tracy\Debugger;
use Tracy\ILogger;

/**
 * Class SendEmailsApplication
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron\Commands
 */
class SendEmailsApplication
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
		$this->mailer = $mailer;
		$this->persistEmail = $persistEmail;
	}

	/**
	 * @param int|null $number Limit of sending emails.
	 * @param SymfonyStyle $output
	 * @return array|int[] Number of successfully|unsuccessful sent emails.
	 */
	public function send(?int $number, SymfonyStyle $output): array
	{
		$emails = $this->persistEmail->loadEmails($number);
		$progress = new ProgressBar($output, count($emails));

		$successful = 0;
		$unsuccessful = 0;
		$progress->display();
		foreach ($emails as $email)
		{
			try
			{
				$this->mailer->send($email);
				$successful++;
			}
			catch (\Throwable $e)
			{
				$unsuccessful++;
				Debugger::log($e, ILogger::EXCEPTION);
			}
			$progress->advance();
		}

		$progress->finish();
		$output->newLine(2);

		return [$successful, $unsuccessful];
	}

}
