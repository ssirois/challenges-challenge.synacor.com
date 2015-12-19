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
