<?php
namespace Synacor\Challenge;

class VirtualMachine implements \SplObserver {
  const STATES = [ 'STARTED' => 0, 'HALTED' => 1, 'CRASHED' => 2, 'IDLE' => 3, 'EXECUTING' => 4 ];
  const MAX_VALUE = 32775;
  # TODO: rename this because the last litteral value is actually 32767
  const LAST_LITTERAL_VALUE = 32768;
  const OPERATIONS = [ 'HALT' => 0, 'OUT' => 19, 'NOOP' => 21 ];

  private $ram;
  private $ramIterator;
  private $state;
  private $stdOut;
  private $interuptHandler;

  public function __construct() {
    $this->ram = new MemoryModule();
    $this->setState(self::STATES['STARTED']);
    $this->stdOut = '';

    $this->interuptHandler = new InteruptHandler();
    $this->interuptHandler->attach($this);
  }

  public function loadProgram($program) {
    while ((($word = $program->getNextWord()) !== FALSE) && ($this->getState() != self::STATES['CRASHED'])) {
      if ($this->isWordValid($word)) {
        $this->ram->push($word);
      }
      else {
        $this->setState(self::STATES['CRASHED']);
      }
    }

    $this->ramIterator = $this->ram->getIterator();
  }

  private function isWordValid($word) {
    /*
     * this test is to avoid warnings 'bout unpacking without having enough data
     * couldn't find a test that would prove this wrong... until then: this code
     * is considered "good"?!? Since when word is shorter than word size, we
     * can assume that it doesn't goes over MAX_VALUE?
     */
    if (strlen($word) < Program::WORD_SIZE)
      return true;

    return (unpack('v', $word)[1] <= self::MAX_VALUE);
  }

  public function executeLoadedProgram() {
    if ($this->getState() == self::STATES['CRASHED'])
      return FALSE;

    $this->setState(self::STATES['EXECUTING']);

    $this->ramIterator->rewind();
    while (
      !is_null($operation = $this->buildOperation($this->ramIterator->current()))
      &&
      ($this->getState() != self::STATES['HALTED'])
    ) {
      $operation->execute();

      $this->ramIterator->next();
    }
  }

  private function buildOperation($memoryValue) {
    if (!is_null($operationNumber = $this->unpackMemoryData($memoryValue)))
      return Operation::getInstance($operationNumber, $this->ramIterator, $this->interuptHandler);
    else
      return NULL;
  }

  /* TODO: this is exposed only because of our "unit tests"... it shouldn't
   * test the behaviour... or make sure to get output from legitimate methods
   * why introduce a method that is only used by our tests... we should refactor
   * this now that we are "elsewhere", thanks to our architecture discovery
   */
  public function getRamValueAtAddress($address) {
    $value = $this->ram->readValueAtAddress($address);
    if ($value === FALSE || strlen($value) < Program::WORD_SIZE)
      return FALSE;

    return $this->unpackMemoryData($value);
  }

  public function getOutput() {
    return $this->stdOut;
  }

  public function getState() {
    return $this->state;
  }

  private function setState($newState) {
    $this->state = $newState;
  }

  private function unpackMemoryData($data) {
    if ($data === FALSE || strlen($data) < Program::WORD_SIZE)
      return NULL;

    return unpack('v', $data)[1] % self::LAST_LITTERAL_VALUE;
  }

  /*
   * SplObserver interface
   */
  public function update(\SplSubject $subject) {
    $interuptSignal = $subject->getSignal();
    switch ($interuptSignal->getName()) {
      case 'OUTPUT':
        $data = $this->unpackMemoryData($interuptSignal->getData());
        $this->stdOut .= chr($data);
        break;
      case 'STATE_CHANGE':
        $this->setState(self::STATES[$interuptSignal->getData()]);
        break;
    }
  }
}
