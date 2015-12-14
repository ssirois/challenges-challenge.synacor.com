Feature: Virtual Machine
  In order to execute challenge.synacor.com programs
  As a geek
  I need a virtual machine that will run the program

  Scenario: Create a new virtual machine
    Given I need to run a challenge program
    When I create a new virtual machine
    Then a new virtual machine should be running
