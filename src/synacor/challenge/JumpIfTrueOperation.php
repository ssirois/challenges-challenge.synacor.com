<?php
namespace Synacor\Challenge;

class JumpIfTrueOperation extends BaseJumpOperation {
  private $nextAddress;
  private $isAssertionTrue;

  public function __construct($memoryIterator) {
    parent::__construct($memoryIterator);

    $this->memoryIterator = $memoryIterator;
    $this->memoryIterator->next();
    $this->isAssertionTrue = unpack('v', $this->memoryIterator->current())[1];
    $this->memoryIterator->next();
    $this->nextAddress = unpack('v', $this->memoryIterator->current())[1];
  }

  public function execute() {
    if ($this->isAssertionTrue == 1) {
      $this->memoryIterator->seek($this->nextAddress);
    }
  }
}