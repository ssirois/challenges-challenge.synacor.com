<?php
namespace Synacor\Challenge;

abstract class Operation {
  const OPERATIONS = [ 'HALT' => 0, 'SET' => 1, 'JMP' => 6, 'JT' => 7, 'JF' => 8, 'OUT' => 19, 'NOOP' => 21 ];

  abstract public function execute();

  final public static function getInstance($operation, \SeekableIterator $memoryIterator, $interuptHandler) {
    switch ($operation) {
      case self::OPERATIONS['OUT']:
        return new OutOperation($memoryIterator, $interuptHandler);
        break;
      case self::OPERATIONS['HALT']:
        return new HaltOperation($interuptHandler);
        break;
      case self::OPERATIONS['NOOP']:
        return new NoOperation($interuptHandler);
        break;
      case self::OPERATIONS['JMP']:
        return new JumpOperation($memoryIterator);
        break;
      case self::OPERATIONS['JT']:
        return new JumpIfTrueOperation($memoryIterator);
        break;
      case self::OPERATIONS['JF']:
        return new JumpIfFalseOperation($memoryIterator);
        break;
      case self::OPERATIONS['SET']:
        return new SetOperation($memoryIterator, $interuptHandler);
        break;
      default:
        return new EmptyOperation();
        break;
    }
  }
}
