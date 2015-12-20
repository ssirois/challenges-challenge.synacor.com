<?php
namespace Synacor\Challenge;

class OutOperation extends Operation {
  private $dataToBeOutput;
  private $interuptHandler;

  public function __construct($memoryIterator, $interuptHandler, $registersSnapshot) {
    $memoryIterator->next();
    $word = $memoryIterator->current();
    if ($word->isOverflowed()) {
      $register = $registersSnapshot[$word->getValue()];
      $this->dataToBeOutput = $register->getValue();
    }
    else {
      $this->dataToBeOutput = $word->getValue();
    }
    $this->interuptHandler = $interuptHandler;
  }

  public function execute() {
    $this->interuptHandler->interupt(new InteruptSignal('OUTPUT', $this->dataToBeOutput));
  }
}
