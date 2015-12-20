<?php
namespace Synacor\Challenge;

class Word {
  const SIZE_IN_BITS = 16;
  const SIZE_IN_BYTES = self::SIZE_IN_BITS / 8;

  const LAST_LITTERAL_VALUE = 32767;
  const OVERFLOW_MODULO = self::LAST_LITTERAL_VALUE + 1;
  const MAX_VALUE = 32775;

  private $packedBits;

  public function __construct($bits) {
    $this->packedBits = $bits;
  }

  public function getValue() {
    $value = $this->getUnpackedBits();

    if ($this->isValid() && $this->isOverflowed()) {
      $value %= self::OVERFLOW_MODULO;
    }

    return $value;
  }

  private function getUnpackedBits() {
    if ($this->packedBits === FALSE || strlen($this->packedBits) < self::SIZE_IN_BYTES)
      return NULL;

    return unpack('v', $this->packedBits)[1];
  }

  public function isValid() {
    /*
     * this test is to avoid warnings 'bout unpacking without having enough data
     * couldn't find a test that would prove this wrong... until then: this code
     * is considered "good"?!? Since when word is shorter than word size, we
     * can assume that it doesn't goes over MAX_VALUE?
     */
    if (strlen($this->packedBits) < self::SIZE_IN_BYTES)
      return true;

    return ($this->getUnpackedBits() <= self::MAX_VALUE);
  }

  public function isOverflowed() {
    $value = $this->getUnpackedBits();

    $overflowed = FALSE;
    if ($value > self::LAST_LITTERAL_VALUE)
      $overflowed = TRUE;

    return $overflowed;
  }
}
