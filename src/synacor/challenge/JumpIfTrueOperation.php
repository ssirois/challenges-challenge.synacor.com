<?php
namespace Synacor\Challenge;

class JumpIfTrueOperation extends BaseJumpOperation {
  private $nextAddress;
  private $isAssertionTrue;

  public function __construct($memoryIterator, $registersSnapshot) {
    parent::__construct($memoryIterator, $registersSnapshot);

    $this->memoryIterator->next();
    $this->isAssertionTrue = $this->memoryIterator->current()->getValue();

    $this->memoryIterator->next();
    $word = $this->memoryIterator->current();
    $this->nextAddress = $this->dereferenceWordUpToValue($word);
  }

  public function execute() {
    if ($this->isAssertionTrue == 1) {
      $this->memoryIterator->seek($this->nextAddress);
    }
  }
}
