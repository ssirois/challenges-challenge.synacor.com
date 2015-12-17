<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

require_once 'vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

use Synacor\Challenge\VirtualMachine;
use Synacor\Challenge\Program;

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

  /**
   * @Given /^a virtual machine is created$/
   */
  public function aVirtualMachineIsCreated()
  {
    $this->vm = new VirtualMachine();
  }

  /**
   * @Given /^the following "([^"]*)" is loaded$/
   */
  public function theFollowingIsLoaded($rawProgram)
  {
    $programCodeStream = $this->getProgramCodeMemoryStream($rawProgram);

    $this->vm->loadProgram(new Program($programCodeStream));

    fclose($programCodeStream);
  }

  /**
   * @Given /^the following program is loaded:$/
   */
  public function theFollowingProgramIsLoaded(PyStringNode $rawProgram)
  {
    $programCodeStream = $this->getProgramCodeMemoryStream($rawProgram->getRaw());

    $this->vm->loadProgram(new Program($programCodeStream));

    fclose($programCodeStream);
  }

  /**
   * @Then /^memory at address space (\d+) should have "([^"]*)"$/
   */
  public function memoryAtAddressSpaceShouldHave($address, $expected)
  {
    $memoryValue = $this->vm->getRamValueAtAddress($address);
    $actual = decbin($memoryValue);
    assertEquals($expected, $actual);
  }

  /**
   * @Given /^integer value of memory at address space (\d+) should be (\d+)$/
   */
  public function integerValueOfMemoryAtAddressSpaceShouldBe($address, $expected)
  {
    $actual = $this->vm->getRamValueAtAddress($address);
    assertEquals($expected, $actual);
  }

  /**
   * @Then /^virtual machine state should be "([^"]*)"$/
   */
  public function virtualMachineStateShouldBe($expected)
  {
    assertEquals(VirtualMachine::STATES["$expected"], $this->vm->getState());
  }

  /**
   * @Given /^we execute loaded program$/
   */
  public function weExecuteLoadedProgram()
  {
    $this->vm->executeLoadedProgram();
  }

  /**
   * @Then /^user should see "([^"]*)"$/
   */
  public function userShouldSee($expected)
  {
    assertEquals($expected, $this->vm->getOutput());
  }

  /**
   * Test Helpers
   */
  private function getProgramCodeMemoryStream($rawProgram)
  {
    $programCodeStream = fopen('php://temp', 'r+b');
    rewind($programCodeStream);

    $programStruct = explode(' ', $rawProgram);

    foreach ($programStruct as $bits) {
      fwrite($programCodeStream, pack('v', bindec($bits)));
    }

    rewind($programCodeStream);
    return $programCodeStream;
  }
}
