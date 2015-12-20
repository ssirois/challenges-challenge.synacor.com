<?php
namespace Synacor\Challenge;

class EmptyOperation extends Operation {
  public function __construct() {
    parent::__construct(NULL);
  }

  public function execute() {
  }
}
