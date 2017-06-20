<?php declare(strict_types = 1);

namespace Oli\EmailSender\Cron\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TestCommand
 * Copyright (c) 2017 Sportisimo s.r.o.
 * @package Oli\EmailSender\Cron\Commands
 */
final class TestCommand extends Command
{

  /**
   * In this method setup command, description and its parameters
   */
  protected function configure()
  {
    $this->setName('hash-password');
    $this->setDescription('Hashes provided password with BCRYPT and prints to output.');
    $this->addArgument('password', InputArgument::REQUIRED, 'Password to be hashed.');
  }

  /**
   * Here all logic happens
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null
   * @throws InvalidArgumentException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $password = $input->getArgument('password');

    $output->writeln(sprintf('Your hashed password is: %s', $password));

    // return value is important when using CI
    // to fail the build when the command fails
    // 0 = success, other values = fail
    return 0;
  }
}