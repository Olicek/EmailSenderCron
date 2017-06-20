#!/usr/bin/env php
<?php  declare(strict_types = 1);


// Create the Application
$application = new Symfony\Component\Console\Application();

// Register all Commands
$application->add(new TestCommand());

// Run it
$application->run();