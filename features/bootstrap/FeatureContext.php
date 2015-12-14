<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Synacor\Challenge\VirtualMachine;

require_once 'vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
  private $vm;

  /**
   * Initializes context.
   * Every scenario gets it's own context object.
   *
   * @param array $parameters context parameters (set them up through behat.yml)
   */
  public function __construct(array $parameters)
  {
      // Initialize your context here
  }

  /**
   * @Given /^I need to run a challenge program$/
   */
  public function iNeedToRunAChallengeProgram()
  {
  }

  /**
   * @When /^I create a new virtual machine$/
   */
  public function iCreateANewVirtualMachine()
  {
    $this->vm = new VirtualMachine();
  }

  /**
   * @Then /^a new virtual machine should be running$/
   */
  public function aNewVirtualMachineShouldBeRunning()
  {
    assertFalse(empty($this->vm));
  }
}
