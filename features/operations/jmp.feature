Feature: Jump operation
  In order to move around in memory
  As a virtual machine
  I need to interprete a jump operation to move the stack pointer to a new memory address

  # TODO: this needs to be refactored... it do not test only one thing, or needs
  # another part of the system to green (halt operation)
  Scenario: Jumping to a valid memory address
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000000110 = jmp operation
    # 0000000000000100 = 4 (the address we want to jump to)
    """
    0000000000000110 0000000000000100 0000000000010011 0000000001000001 0000000000000000 0000000000010011 0000000001000001
    """
    And we execute loaded program
    Then current memory address should be 4

  Scenario: Jumping to an address that has been stored to a register (indirect jump)
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000000001 = set operation
    # 1000000000000000 = address of register 0
    # 0000000000000110 = 6 (adress to which we want to go indirectly)
    # 0000000000000110 = jmp operation
    """
    0000000000000001 1000000000000000 0000000000000110 0000000000000110 1000000000000000 0000000000000001 0000000000000000
    """
    And we execute loaded program
    Then current memory address should be 6

  Scenario: Bouncing around with registers (recursive jump)
    Given a virtual machine is created
    And the following program is loaded:
    """
    0000000000000001 1000000000000000 1000000000000001 0000000000000001 1000000000000001 0000000000001001 0000000000000110 1000000000000000 0000000000000000 0000000000000000 0000000100000001
    """
    And we execute loaded program
    Then current memory address should be 9
