<?php
namespace Synacor\Challenge;

class VirtualMachine {
  const STATES = [ 'STARTED' => 0, 'HALTED' => 1, 'CRASHED' => 2, 'IDLE' => 3, 'EXECUTING' => 4 ];
  const MAX_VALUE = 32775;
  # TODO: rename this because the last litteral value is actually 32767
  const LAST_LITTERAL_VALUE = 32768;
  const OPERATIONS = [ 'HALT' => 0, 'OUT' => 19, 'NOOP' => 21 ];

  private $ram;
  private $state;
  private $stdOut;

  public function __construct() {
    $this->ram = new MemoryModule();
    $this->setState(self::STATES['STARTED']);
    $this->stdOut = '';
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

    $currentMemoryAddress = 0;
    do {
      $operation = $this->getRamValueAtAddress($currentMemoryAddress);
      if ($operation === FALSE) {
        return FALSE;
      }

      switch ($operation) {
        case self::OPERATIONS['OUT']:
          $currentMemoryAddress++;
          $this->stdOut .= chr($this->getRamValueAtAddress($currentMemoryAddress));
          break;
        case self::OPERATIONS['HALT']:
          $this->setState(self::STATES['HALTED']);
          break 2;
        case self::OPERATIONS['NOOP']:
          $this->setState(self::STATES['IDLE']);
          break;
      }

      $currentMemoryAddress++;
    } while (($currentMemoryAddress < $this->ram->getUsedMemorySize()) && ($this->getState() != self::STATES['HALTED']));
  }

  public function getRamValueAtAddress($address) {
    $value = $this->ram->readValueAtAddress($address);
    if ($value === FALSE || strlen($value) < Program::WORD_SIZE)
      return FALSE;

    return unpack('v', $value)[1] % self::LAST_LITTERAL_VALUE;
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
}
