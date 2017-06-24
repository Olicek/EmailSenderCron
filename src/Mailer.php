<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron;
use Oli\EmailSender\Cron\Adapters\IAdapter;
use Oli\EmailSender\Persistence\Entities\IEmail;

/**
 * Class Mailer
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Persistence
 */
class Mailer implements IMailer
{

	/**
	 * @var IAdapter
	 */
	private $adapter;

	public function __construct(IAdapter $adapter)
	{
		$this->adapter = $adapter;
	}

	public function send(IEmail $email): void
	{
		$this->adapter->send($email);
	}

}
