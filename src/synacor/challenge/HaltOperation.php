<?php
namespace Synacor\Challenge;

class HaltOperation extends Operation {
  private $interuptHandler;

  public function __construct($interuptHandler, $registersSnapshot) {
    parent::__construct($registersSnapshot);

    $this->interuptHandler = $interuptHandler;
  }

  public function execute() {
    $this->interuptHandler->interupt(new InteruptSignal('STATE_CHANGE', 'HALTED'));
  }
}
