Feature: Out Operation
  In order to interact with user
  As a virtual machine
  I need to interprete an out operation to send a message to user

  Scenario: Executing an out operation
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000010011 = out operation
    # 0000000001000001 = 65 = ascii code for 'A'
    """
    0000000000010011 0000000001000001
    """
    And we execute loaded program
    Then user should see "A"

  Scenario: Executing multiple out operations
    Given a virtual machine is created
    And the following program is loaded:
    # 0000000000010011 = out operation
    # 0000000001000001 = 65 = ascii code for 'A'
    # 0000000001000010 = 66 = ascii code for 'B'
    """
    0000000000010011 0000000001000001 0000000000010011 0000000001000010
    """
    And we execute loaded program
    Then user should see "AB"
