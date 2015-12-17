<?php
namespace Synacor\Challenge;

class MemoryModule {
  private $memoryStack;

  public function __construct() {
    $this->memoryStack = array();
  }

  public function readValueAtAddress($address) {
    return $this->memoryStack[$address];
  }

  public function push($value) {
    array_push($this->memoryStack, $value);
  }

  public function getUsedMemorySize() {
    return count($this->memoryStack);
  }
}
