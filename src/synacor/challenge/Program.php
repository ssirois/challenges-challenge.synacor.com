<?php
namespace Synacor\Challenge;

class Program {
  const WORD_SIZE = 2; // bytes (or 16 bits)

  private $stream;

  public function __construct($stream) {
    $this->stream = $stream;
  }

  public function getNextWord() {
    if (feof($this->stream))
      return FALSE;
    else
      return fread($this->stream, self::WORD_SIZE);
  }
}
