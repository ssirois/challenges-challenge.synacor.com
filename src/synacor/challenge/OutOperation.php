<?php
namespace Synacor\Challenge;

class OutOperation extends Operation {
  private $dataToBeOutput;
  private $interuptHandler;

  public function __construct($memoryIterator, $interuptHandler) {
    $memoryIterator->next();
    $this->dataToBeOutput = $memoryIterator->current();
    $this->interuptHandler = $interuptHandler;
  }

  public function execute() {
    $this->interuptHandler->interupt(new InteruptSignal('OUTPUT', $this->dataToBeOutput));
  }
}
