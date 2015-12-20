<?php
namespace Synacor\Challenge;

abstract class Operation {
  const OPERATIONS = [ 'HALT' => 0, 'SET' => 1, 'JMP' => 6, 'JT' => 7, 'JF' => 8, 'OUT' => 19, 'NOOP' => 21 ];

  protected $registersSnapshot;

  public function __construct($registersSnapshot) {
    $this->registersSnapshot = $registersSnapshot;
  }

  abstract public function execute();

  final public static function getInstance($operation, \SeekableIterator $memoryIterator, $interuptHandler, $registersSnapshot) {
    switch ($operation) {
      case self::OPERATIONS['OUT']:
        return new OutOperation($memoryIterator, $interuptHandler, $registersSnapshot);
        break;
      case self::OPERATIONS['HALT']:
        return new HaltOperation($interuptHandler, $registersSnapshot);
        break;
      case self::OPERATIONS['NOOP']:
        return new NoOperation($interuptHandler, $registersSnapshot);
        break;
      case self::OPERATIONS['JMP']:
        return new JumpOperation($memoryIterator, $registersSnapshot);
        break;
      case self::OPERATIONS['JT']:
        return new JumpIfTrueOperation($memoryIterator, $registersSnapshot);
        break;
      case self::OPERATIONS['JF']:
        return new JumpIfFalseOperation($memoryIterator, $registersSnapshot);
        break;
      case self::OPERATIONS['SET']:
        return new SetOperation($memoryIterator, $interuptHandler, $registersSnapshot);
        break;
      default:
        return new EmptyOperation();
        break;
    }
  }

  protected function dereferenceWordUpToValue($word) {
    if (is_object($word) && $word->isOverflowed())
      $word = $this->dereferenceWordUpToValue($this->registersSnapshot[$word->getValue()]);

    if (is_object($word))
      return $word->getValue();
    else
      return $word;
  }
}
