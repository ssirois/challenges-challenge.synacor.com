Feature: Set operation
  In order to store values in registers
  As a virtual machine
  I need to interprete a set operation to put a value inside a register

  Scenario: Put a value inside a register
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000000001 = set operation
    # 1000000000000000 = address of register 0
    # 0000000000000010 = 2 (arbitrary value to store in register)
    """
    0000000000000001 1000000000000000 0000000000000010
    """
    And we execute loaded program
    Then register 0 value should be 2
