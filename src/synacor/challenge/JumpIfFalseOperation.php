<?php
namespace Synacor\Challenge;

class JumpIfFalseOperation extends BaseJumpOperation {
  private $nextAddress;
  private $isAssertionTrue;

  public function __construct($memoryIterator, $registersSnapshot) {
    parent::__construct($memoryIterator);

    $this->memoryIterator->next();
    $this->isAssertionTrue = $this->memoryIterator->current()->getValue();

    $this->memoryIterator->next();
    $word = $this->memoryIterator->current();
    if ($word->isOverflowed()) {
      $register = $registersSnapshot[$word->getValue()];
      $this->nextAddress = $register->getValue();
    }
    else {
      $this->nextAddress = $word->getValue();
    }
  }

  public function execute() {
    if ($this->isAssertionTrue == 0) {
      $this->memoryIterator->seek($this->nextAddress);
    }
  }
}
