Feature: No Operation
  In order to do nothing
  As a virtual machine
  I need to interprete a noop (no operation) that does nothing at all

  Scenario: Executing a noop operation
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000010101 = noop
    """
    0000000000010101
    """
    And we execute loaded program
    Then virtual machine state should be "IDLE"
