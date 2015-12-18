<?php
namespace Synacor\Challenge;

class InteruptSignal {
  private $name;
  private $data;

  public function __construct($name, $data = NULL) {
    $this->name = $name;
    $this->data = $data;
  }

  public function getName() {
    return $this->name;
  }

  public function getData() {
    return $this->data;
  }
}
