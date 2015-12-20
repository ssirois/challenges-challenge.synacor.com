<?php
namespace Synacor\Challenge;

class OutOperation extends Operation {
  private $dataToBeOutput;
  private $interuptHandler;

  public function __construct($memoryIterator, $interuptHandler, $registersSnapshot) {
    parent::__construct($registersSnapshot);

    $memoryIterator->next();
    $word = $memoryIterator->current();
    $this->dataToBeOutput = $this->dereferenceWordUpToValue($word);
    $this->interuptHandler = $interuptHandler;
  }

  public function execute() {
    $this->interuptHandler->interupt(new InteruptSignal('OUTPUT', $this->dataToBeOutput));
  }
}
