<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Adapters;

use Oli\EmailSender\Persistence\Entities\IEmail;
use Oli\EmailSender\Cron\Exceptions\SendException;

/**
 * Class NativeMail
 * Copyright (c) 2017 Petr Olišar
 * @package Oli\EmailSender\Cron\Adapters
 */
class NativeMail implements IAdapter
{

	public function send(IEmail $email): void
	{
		$headers = 'From: ' . $email->getFrom()
				->getEmail() . "\r\n" . 'Reply-To: ' . $email->getReplyTo()
					   ->getEmail() . "\r\n" . 'X-Mailer: PHP/' . phpversion();

		if(!mail(implode(',', $email->getRecipients()), $email->getSubject(), $email->getMessage(), $headers))
		{
			throw new SendException($errorMessage = error_get_last()['message']);
		}
	}

}
