<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Adapters;

use Oli\EmailSender\Persistence\Entities\IEmail;

/**
 * Class IAdapter
 * Copyright (c) 2017 Petr Olišar
 * @package Oli\EmailSender\Cron\Adapters
 */
interface IAdapter
{

	public function send(IEmail $email): void;

}
