<?php
namespace Synacor\Challenge;

abstract class BaseJumpOperation extends Operation {
  protected $memoryIterator;

  public function __construct($memoryIterator) {
    $this->memoryIterator = $memoryIterator;
  }
}
