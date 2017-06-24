<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Adapters;

use Oli\EmailSender\Cron\Config\MailerSettings;
use Oli\EmailSender\Persistence\Entities\IEmail;
use Oli\EmailSender\Cron\Exceptions\SendException;

/**
 * Class PHPMailer
 * Copyright (c) 2017 Petr Olišar
 * @package Oli\EmailSender\Cron\Adapters
 */
class PHPMailer implements IAdapter
{

	/**
	 * @var array
	 */
	private $settings;

	public function __construct(MailerSettings $settings)
	{
		$this->settings = $settings;
	}

	public function send(IEmail $email): void
	{
		$mailer = new \PHPMailer();

		if($this->settings->isSMTP())
		{
			//$mailer->SMTPDebug = 3;             // Enable verbose debug output
			$mailer->isSMTP();
			$mailer->Host = $this->settings->getHost();
			$mailer->SMTPAuth = $this->settings->getSmtpAuth();
			$mailer->Username = $this->settings->getUsername();
			$mailer->Password = $this->settings->getPassword();
			$mailer->SMTPSecure = $this->settings->getSmtpSecure();
			$mailer->Port = $this->settings->getPort();
		}

		$mailer->setFrom(
			$email->getFrom()
				->getEmail(), $email->getFrom()
				->getName()
		);
		foreach ($email->getRecipients() as $recipient)
		{
			$mailer->addAddress($recipient->getEmail(), $recipient->getName());
		}
		$mailer->addReplyTo(
			$email->getReplyTo()
				->getEmail(), $email->getReplyTo()
				->getName()
		);

		foreach ($email->getAttachments() as $attachment)
		{
			$mailer->addAttachment($attachment);
		}
		$mailer->isHTML(true);

		$mailer->Subject = $email->getSubject();
		$mailer->Body = $email->getMessage();
		$mailer->AltBody = 'This is the body in plain text for non-HTML mailer clients';
		$mailer->CharSet = $this->settings->getCharSet();

		if(!$mailer->send())
		{
			throw new SendException($mailer->ErrorInfo);
		}
	} // send()

}
