<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$sender = new \Oli\EmailSender\Persistence\Entities\Person('Petr Olišar', 'petr.olisar@gmail.com');
$reciver = new \Oli\EmailSender\Persistence\Entities\Person('František Vomáčka', 'vomacka@email.cz');

$email = new \Oli\EmailSender\Persistence\Entities\Email($sender, $reciver, 'Ahoj Františku, jak se máš?', 'Testovací e-mail');

$options = [
	'driver'   => 'mysql',
	'host'     => 'localhost',
	'username' => 'root',
	'password' => '***',
	'database' => 'table',
];
$connection = new \Dibi\Connection($options);

$dibiAdapter = new \Oli\EmailSender\Persistence\Adapters\DibiAdapter($connection);

$dibiAdapter->insertEmail($email);