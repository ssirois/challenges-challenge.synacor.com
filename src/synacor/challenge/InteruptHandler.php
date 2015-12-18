<?php
namespace Synacor\Challenge;

class InteruptHandler implements \SplSubject {
  private $observers;
  private $interuptSignal;

  public function __construct() {
    $this->observers = array();
  }

  public function interupt($interuptSignal) {
    $this->interuptSignal = $interuptSignal;
    $this->notify();
  }

  public function getSignal() {
    return $this->interuptSignal;
  }

  /*
   * SplSubject interface
   */
  public function attach(\SplObserver $observer) {
    $this->observers[] = $observer;
  }

  public function detach(\SplObserver $observer) {
    $observerIndex = array_search($observer, $this->observers, true);
    if ($observerIndex)
      unset($this->observers[$observerIndex]);
  }

  public function notify() {
    foreach ($this->observers as $observer) {
      $observer->update($this);
    }
  }
}
