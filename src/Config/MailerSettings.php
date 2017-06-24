<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Config;

/**
 * Class MailerSettings
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron\Config
 */
class MailerSettings
{

	/**
	 * @var array
	 */
	private $data;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	public function isSMTP(): bool
	{
		return $this->data['useSMTP'];
	}

	public function getHost(): string
	{
		return $this->data['host'];
	}

	public function getSmtpAuth(): bool
	{
		return $this->data['smtpAuth'];
	}

	public function getUsername(): string
	{
		return $this->data['username'];
	}

	public function getPassword(): string
	{
		return $this->data['password'];
	}

	public function getSmtpSecure(): ?string
	{
		return $this->data['smtpSecure'];
	}

	public function getPort(): int
	{
		return $this->data['port'];
	}

	public function getCharSet(): string
	{
		return $this->data['charset'];
	} // getCharSet()

}
