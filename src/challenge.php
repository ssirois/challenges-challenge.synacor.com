<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Synacor\Challenge\VirtualMachine;
use Synacor\Challenge\Program;

$vm = new VirtualMachine(STDOUT);

$programFile = fopen(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'challenge.bin', 'rb');
$vm->loadProgram(new Program($programFile));
$vm->executeLoadedProgram();

fclose($programFile);
