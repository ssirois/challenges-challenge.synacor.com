<?php
namespace Synacor\Challenge;

class JumpOperation extends BaseJumpOperation {
  private $nextAddress;

  public function __construct($memoryIterator) {
    parent::__construct($memoryIterator);

    $this->memoryIterator = $memoryIterator;
    $this->memoryIterator->next();
    $this->nextAddress = unpack('v', $memoryIterator->current())[1];
  }

  public function execute() {
    $this->memoryIterator->seek($this->nextAddress);
  }
}
