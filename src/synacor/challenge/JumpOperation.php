<?php
namespace Synacor\Challenge;

class JumpOperation extends BaseJumpOperation {
  private $nextAddress;

  public function __construct($memoryIterator, $registersSnapshot) {
    parent::__construct($memoryIterator, $registersSnapshot);

    $this->memoryIterator->next();
    $word = $this->memoryIterator->current();
    $this->nextAddress = $this->dereferenceWordUpToValue($word);
  }

  public function execute() {
    $this->memoryIterator->seek($this->nextAddress);
  }
}
