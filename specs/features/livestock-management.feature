Feature: Livestock Management
  As a slaughterhouse operator
  I want to manage livestock stock and quality grading
  So that I can track slaughtered animals and their classifications

  Background:
    Given I am logged into the Wobb system
    And I have the necessary permissions for livestock management

  Scenario: Register new stock entry
    Given I am on the stock management page
    When I enter stock information with VVVO number "12345678"
    And I set the buyer as "Mueller AG"
    And I set the weight as "85.5" kg
    And I select quality grade "E"
    And I save the stock entry
    Then the stock should be registered in the system
    And the stock should have quality grade "E"
    And the stock should show weight "85.5" kg

  Scenario: Classify stock with quality grades (Handelsklasse)
    Given I have a stock entry with VVVO "12345678"
    When I classify the stock with quality grade "S"
    Then the stock should be marked as quality "S"
    And the stock should be included in "S" grade statistics

  Scenario: Handle non-quality stock classifications
    Given I have a stock entry with VVVO "12345678"
    When I classify the stock with non-quality grade "M"
    Then the stock should be marked as non-quality "M"
    And the stock should be excluded from standard quality statistics
    And the stock should be included in non-quality reporting

  Scenario: Record damage codes (Schadencodes)
    Given I have a stock entry with VVVO "12345678"
    When I assign damage code "03" to the stock
    Then the stock should have damage code "03" recorded
    And the damage should be reflected in damage statistics

  Scenario: Validate quality grade values
    Given I am entering stock information
    When I try to assign an invalid quality grade "X"
    Then I should see an error message "Invalid quality grade"
    And the valid quality grades should be displayed: "S, E, U, R, O, P, Z"

  Scenario: Piggery operations tracking
    Given I have a piggery operation for company "Schweinehof GmbH"
    When I record the start date as "2024-01-15"
    And I record the end date as "2024-01-22"
    And I save the piggery operation
    Then the operation should be tracked for the specified period
    And the operation should be associated with "Schweinehof GmbH"

  Scenario: Calculate stock statistics by quality
    Given I have stock entries with the following qualities:
      | VVVO     | Quality | Weight |
      | 12345678 | S       | 85.5   |
      | 12345679 | E       | 92.0   |
      | 12345680 | S       | 78.3   |
    When I generate quality statistics
    Then I should see:
      | Quality | Count | Total Weight |
      | S       | 2     | 163.8        |
      | E       | 1     | 92.0         |

  Scenario: Track stock by buyer
    Given I have stock entries for different buyers:
      | VVVO     | Buyer      | Weight |
      | 12345678 | Mueller AG | 85.5   |
      | 12345679 | Schmidt Co | 92.0   |
      | 12345680 | Mueller AG | 78.3   |
    When I filter stock by buyer "Mueller AG"
    Then I should see 2 stock entries
    And the total weight should be "163.8" kg