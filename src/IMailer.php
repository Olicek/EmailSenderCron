<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron;

use Oli\EmailSender\Persistence\Entities\IEmail;

/**
 * Class IMailer
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron
 */
interface IMailer
{

  public function send(IEmail $email): void;

}
