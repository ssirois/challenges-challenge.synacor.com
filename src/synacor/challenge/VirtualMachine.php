<?php
namespace Synacor\Challenge;

class VirtualMachine {
  const STATES = [ "STARTED" => 0, "STOPPED" => 1, "CRASHED" => 2 ];
  const MAX_VALUE = 32775;
  # TODO: rename this because the last litteral value is actually 32767
  const LAST_LITTERAL_VALUE = 32768;

  private $ram;
  private $state;

  public function __construct() {
    $this->ram = array();
    $this->state = self::STATES["STARTED"];
  }

  public function loadProgram($program) {
    $address = 0;
    while (($word = $program->getNextWord()) !== FALSE) {
      if ($this->isWordValid($word)) {
        $this->ram[$address] = $word;
      }
      else {
        $this->state = self::STATES["CRASHED"];
        break;
      }

      $address++;
    }
  }

  private function isWordValid($word) {
    # this test is to avoid warnings 'bout unpacking without having enough data
    # couldn't find a test that would prove this wrong... until then: this code
    # is considered "good"?!? Returning true of false doesn't brake anything?!?
    if (strlen($word) < Program::WORD_SIZE)
      return;

    return (unpack('v', $word)[1] <= self::MAX_VALUE);
  }

  public function getMemoryValueAtAddress($address) {
    return unpack('v', $this->ram[$address])[1] % self::LAST_LITTERAL_VALUE;
  }

  public function isCrashed() {
    return ($this->state == self::STATES["CRASHED"]);
  }
}
