<?php
namespace Synacor\Challenge;

class VirtualMachine implements \SplObserver {
  const STATES = [ 'STARTED' => 0, 'HALTED' => 1, 'CRASHED' => 2, 'IDLE' => 3, 'EXECUTING' => 4 ];
  const OPERATIONS = [ 'HALT' => 0, 'OUT' => 19, 'NOOP' => 21 ];

  private $registers;
  private $ram;
  private $ramIterator;
  private $state;
  private $stdOut;
  private $interuptHandler;

  public function __construct($stdOut) {
    $this->initRegisters();
    $this->ram = new MemoryModule();
    $this->setState(self::STATES['STARTED']);
    $this->stdOut = $stdOut;

    $this->interuptHandler = new InteruptHandler();
    $this->interuptHandler->attach($this);
  }

  private function initRegisters() {
    $this->registers = array(0 => NULL,
                             1 => NULL,
                             2 => NULL,
                             3 => NULL,
                             4 => NULL,
                             5 => NULL,
                             6 => NULL,
                             7 => NULL
                            );
  }

  public function loadProgram($program) {
    while ((($word = $program->getNextWord()) !== FALSE) && ($this->getState() != self::STATES['CRASHED'])) {
      if ($word->isValid()) {
        $this->ram->push($word);
      }
      else {
        $this->setState(self::STATES['CRASHED']);
      }
    }

    $this->ramIterator = $this->ram->getIterator();
  }

  public function executeLoadedProgram() {
    if ($this->getState() == self::STATES['CRASHED'])
      return FALSE;

    $this->setState(self::STATES['EXECUTING']);

    $this->ramIterator->rewind();
    while (
      !is_null($operation = $this->buildOperation($this->ramIterator->current()->getValue()))
      &&
      ($this->getState() != self::STATES['HALTED'])
    ) {
      $operation->execute();

      // todo: simpliest code for our program to work for now...
      // but this is quite ugly.
      // let's hope we discover how to bump this out! ;)
      if (
        !($operation instanceof BaseJumpOperation)
        &&
        ($this->getState() != self::STATES['HALTED'])
      ) {
        $this->ramIterator->next();
      }
    }
  }

  private function buildOperation($memoryValue) {
    if (!is_null($operationNumber = $memoryValue))
      return Operation::getInstance($operationNumber, $this->ramIterator, $this->interuptHandler, $this->registers);
    else
      return NULL;
  }

  /* TODO: this is exposed only because of our "unit tests"... it shouldn't
   * test the behaviour... or make sure to get output from legitimate methods
   * why introduce a method that is only used by our tests... we should refactor
   * this now that we are "elsewhere", thanks to our architecture discovery
   */
  public function getRamValueAtAddress($address) {
    $word = $this->ram->readValueAtAddress($address);
    if ($word->getValue() === NULL)
      return FALSE;

    return $word->getValue();
  }

  /* TODO: @see todo comment on getRamValueAtAddress method
   *
   */
  public function getCurrentMemoryAddress() {
    return $this->ramIterator->key();
  }

  /* TODO: @see todo comment on getRamValueAtAddress method
   *
   */
  public function getRegisterValue($register) {
    $word = $this->registers[$register];
    return $word->getValue();
  }

  public function getState() {
    return $this->state;
  }

  private function setState($newState) {
    $this->state = $newState;
  }

  /*
   * SplObserver interface
   */
  public function update(\SplSubject $subject) {
    $interuptSignal = $subject->getSignal();
    switch ($interuptSignal->getName()) {
      case 'OUTPUT':
        $data = $interuptSignal->getData();
        fwrite($this->stdOut, chr($data));
        break;
      case 'STATE_CHANGE':
        $this->setState(self::STATES[$interuptSignal->getData()]);
        break;
      case 'WRITE_2_REGISTER':
        $register = array_keys($interuptSignal->getData())[0];
        $dataToStore = $interuptSignal->getData()[$register];
        $this->registers[$register] = $dataToStore;
        break;
    }
  }
}
