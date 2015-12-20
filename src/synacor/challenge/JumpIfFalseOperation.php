<?php
namespace Synacor\Challenge;

class JumpIfFalseOperation extends BaseJumpOperation {
  private $nextAddress;
  private $isAssertionTrue;

  public function __construct($memoryIterator) {
    parent::__construct($memoryIterator);

    $this->memoryIterator->next();
    $this->isAssertionTrue = $this->memoryIterator->current()->getValue();
    $this->memoryIterator->next();
    $this->nextAddress = $this->memoryIterator->current()->getValue();
  }

  public function execute() {
    if ($this->isAssertionTrue == 0) {
      $this->memoryIterator->seek($this->nextAddress);
    }
  }
}
