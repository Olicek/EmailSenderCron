<?php declare(strict_types = 1);


namespace Oli\EmailSender\Cron\Adapters;

use Oli\EmailSender\Persistence\Entities\IEmail;
use Oli\EmailSender\Cron\Exceptions\SendException;

/**
 * Class PHPMailer
 * Copyright (c) 2017 Petr Olišar
 * @package Oli\EmailSender\Cron\Adapters
 */
class PHPMailer
{

	public function send(IEmail $email): void
	{
		$mailer = new \PHPMailer();

		//$mailer->SMTPDebug = 3;             // Enable verbose debug output

		$mailer->setFrom($email->getFrom()->getEmail(), $email->getFrom()->getName());
		foreach ($email->getRecipients() as $recipient)
		{
			$mailer->addAddress($recipient->getEmail(), $recipient->getName());
		}
		$mailer->addReplyTo($email->getReplyTo()->getEmail(), $email->getReplyTo()->getName());

		foreach ($email->getAttachments() as $attachment)
		{
			$mailer->addAttachment($attachment);
		}
		$mailer->isHTML(true);

		$mailer->Subject = $email->getSubject();
		$mailer->Body = $email->getMessage();
		$mailer->AltBody = 'This is the body in plain text for non-HTML mailer clients';

		if(!$mailer->send())
		{
			throw new SendException($mailer->ErrorInfo);
		}
	} // send()

}
