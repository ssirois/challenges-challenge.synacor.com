<?php
namespace Synacor\Challenge;

class JumpOperation extends BaseJumpOperation {
  private $nextAddress;

  public function __construct($memoryIterator) {
    parent::__construct($memoryIterator);

    $this->memoryIterator = $memoryIterator;
    $this->memoryIterator->next();
    $this->nextAddress = $memoryIterator->current()->getValue();
  }

  public function execute() {
    $this->memoryIterator->seek($this->nextAddress);
  }
}
