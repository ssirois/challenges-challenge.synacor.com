<?php
namespace Synacor\Challenge;

abstract class Operation {
  const OPERATIONS = [ 'HALT' => 0, 'OUT' => 19, 'NOOP' => 21 ];

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
      default:
        return new EmptyOperation();
        break;
    }
  }
}
