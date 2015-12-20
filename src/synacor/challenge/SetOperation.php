<?php
namespace Synacor\Challenge;

class SetOperation extends Operation {
  private $register;
  private $dataToStore;
  private $interuptHandler;

  public function __construct($memoryIterator, $interuptHandler) {
    $memoryIterator->next();
    $this->register = $memoryIterator->current()->getValue();
    $memoryIterator->next();
    $this->dataToStore = $memoryIterator->current();
    $this->interuptHandler = $interuptHandler;
  }

  public function execute() {
    $this->interuptHandler->interupt(new InteruptSignal('WRITE_2_REGISTER', array($this->register => $this->dataToStore)));
  }
}
