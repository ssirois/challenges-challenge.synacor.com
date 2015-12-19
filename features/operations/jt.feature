Feature: Jump if true operation
  In order to conditionally move around in memory
  As a virtual machine
  I need to interprete a "jump if true" operation (jt) to move the stack pointer to a new memory address if condition is true

  Scenario: Jumping because test is TRUE
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000000111 = jt operation
    # 0000000000000001 = test is true
    # 0000000000000100 = 4 (the address we want to jump to)
    """
    0000000000000111 0000000000000001 0000000000000100 0000000000010011 0000000000000000 0000000001000001
    """
    And we execute loaded program
    Then current memory address should be 4

  Scenario: Not jumping because test is FALSE
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000000111 = jt operation
    # 0000000000000000 = test is false
    # 0000000000000100 = 4 (the address we would jump to)
    """
    0000000000000111 0000000000000000 0000000000000100 0000000000010011 0000000000000001 0000000000000000
    """
    And we execute loaded program
    Then current memory address should be 5
