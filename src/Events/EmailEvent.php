<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Events;

use Oli\EmailSender\Persistence\Entities\IEmail;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EmailEvent
 * Copyright (c) 2017 Sportisimo s.r.o.
 */
class EmailEvent extends Event
{

	/**
	 * @var int
	 */
	private $email;

	public function __construct(IEmail $email)
	{
		$this->email = $email;
	}

	public function getEmail(): IEmail
	{
		return $this->email;
	}

}
