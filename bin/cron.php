#!/usr/bin/env php
<?php  declare(strict_types = 1);


$autoloaderInWorkingDirectory = getcwd() . '/vendor/autoload.php';
if (is_file($autoloaderInWorkingDirectory)) {
  require_once($autoloaderInWorkingDirectory);
}
if (!class_exists('PHPStan\Command\AnalyseCommand', true)) {
  $composerAutoloadFile = __DIR__ . '/../vendor/autoload.php';
  if (!is_file($composerAutoloadFile)) {
    $composerAutoloadFile = __DIR__ . '/../../../autoload.php';
  }
  require_once($composerAutoloadFile);
}

// Create the Application
$application = new Symfony\Component\Console\Application();

// Register all Commands
$application->add(new TestCommand());

// Run it
$application->run();