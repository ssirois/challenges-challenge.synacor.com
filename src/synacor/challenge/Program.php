<?php
namespace Synacor\Challenge;

class Program {
  private $stream;

  public function __construct($stream) {
    $this->stream = $stream;
  }

  public function getNextWord() {
    if (feof($this->stream))
      return FALSE;
    else
      return new Word(fread($this->stream, Word::SIZE_IN_BYTES));
  }

  public function isValid() {
  }
}
