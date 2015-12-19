Feature: Jump if false operation
  In order to conditionally move around in memory
  As a virtual machine
  I need to interprete a "jump if false" operation (jf) to move the stack pointer to a new memory address if condition is false

  Scenario: Jumping because test is FALSE
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000001000 = jf operation
    # 0000000000000000 = test is false
    # 0000000000000100 = 4 (the address we want to jump to)
    """
    0000000000001000 0000000000000000 0000000000000100 0000000000010011 0000000000000000 0000000001000001
    """
    And we execute loaded program
    Then current memory address should be 4

  Scenario: Not jumping because test is TRUE
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000001000 = jf operation
    # 0000000000000001 = test is true
    # 0000000000000100 = 4 (the address we want to jump to)
    """
    0000000000001000 0000000000000001 0000000000000100 0000000000010011 0000000001000001 0000000000000000 
    """
    And we execute loaded program
    Then current memory address should be 5
