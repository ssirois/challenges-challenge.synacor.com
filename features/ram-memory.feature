# Those are more unit tests than behavior tests... but I needed those to feel
# safe reading bits in "ram" correctly.
Feature: Ram Memory
  In order to execute challenge.synacor.com programs
  As a virtual machine
  I need ram memory that will properly store program instructions

  Scenario Outline: Loading 16-bit little-endian in memory
    Given a virtual machine is created
    And the following <program> is loaded
    Then memory at address space <address> should have <expectedValue>
    # 0000000000001111 = 15
    # 0000000000000001 = 1
    Examples:
      | program                                              | address | expectedValue      |
      | "0000000000001111"                                   | 0       | "0000000000001111" |
      | "0000000000000000"                                   | 0       | "0000000000000000" |
      | "0000000000000001"                                   | 0       | "0000000000000001" |
      | "0000000000000001 0000000000000000"                  | 0       | "0000000000000001" |
      | "0000000000000001 0000000000000000"                  | 1       | "0000000000000000" |
      | "0000000000000000 0000000000000001"                  | 0       | "0000000000000000" |
      | "0000000000000000 0000000000000000 0000000000001111" | 2       | "0000000000001111" |

  Scenario: Maximum literal value (sentinel value)
    Given a virtual machine is created
    And the following program is loaded:
    # 0111111111111111 = 32767
      """
      0111111111111111
      """
    Then integer value of memory at address space 0 should be 32767

  Scenario: Register 0 (sentinel value)
    Given a virtual machine is created
    And the following program is loaded:
    # 1000000000000101 = 32768
      """
      1000000000000101
      """
    Then integer value of memory at address space 0 should be 5

  Scenario: Register 7 (sentinel value)
    Given a virtual machine is created
    And the following program is loaded:
    # 1000000000000111 = 32775
      """
      1000000000000111
      """
    Then integer value of memory at address space 0 should be 7

  Scenario: Doing math ("overflow" but still valid value)
    Given a virtual machine is created
    And the following program is loaded:
    # 1000000000000101 = 32768
      """
      1000000000000101
      """
    Then integer value of memory at address space 0 should be 5

  Scenario: Doing math ("overflow": first invalid value)
    Given a virtual machine is created
    And the following program is loaded:
    # 1000000000001000 = 32776
      """
      1000000000001000
      """
    Then virtual machine state should be "CRASHED"
