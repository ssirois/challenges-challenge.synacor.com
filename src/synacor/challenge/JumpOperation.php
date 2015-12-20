<?php
namespace Synacor\Challenge;

class JumpOperation extends BaseJumpOperation {
  private $nextAddress;

  public function __construct($memoryIterator, $registersSnapshot) {
    parent::__construct($memoryIterator);

    $this->memoryIterator->next();
    $word = $this->memoryIterator->current();
    if ($word->isOverflowed()) {
      $register = $registersSnapshot[$word->getValue()];
      $this->nextAddress = $register->getValue();
    }
    else {
      $this->nextAddress = $memoryIterator->current()->getValue();
    }
  }

  public function execute() {
    $this->memoryIterator->seek($this->nextAddress);
  }
}
