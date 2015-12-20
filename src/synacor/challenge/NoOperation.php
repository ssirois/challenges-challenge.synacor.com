<?php
namespace Synacor\Challenge;

class NoOperation extends Operation {
  private $interuptHandler;

  public function __construct($interuptHandler, $registersSnapshot) {
    parent::__construct($registersSnapshot);

    $this->interuptHandler = $interuptHandler;
  }

  public function execute() {
    $this->interuptHandler->interupt(new InteruptSignal('STATE_CHANGE', 'IDLE'));
  }
}
