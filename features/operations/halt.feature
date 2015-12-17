Feature: Halt Operation
  In order to end a program
  As a virtual machine
  I need to interprete a halt operation

  Scenario: Executing a halt operation
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000000000 = halt operation
    """
    0000000000000000
    """
    And we execute loaded program
    Then virtual machine state should be "HALTED"
