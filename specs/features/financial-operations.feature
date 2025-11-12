Feature: Financial Operations
  As a slaughterhouse financial manager
  I want to manage invoices, billing, and financial adjustments
  So that I can maintain accurate financial records and process payments

  Background:
    Given I am logged into the Wobb system
    And I have financial management permissions

  Scenario: Create a new invoice (voucher)
    Given I am on the invoice management page
    When I create a new invoice with type "voucher"
    And I set the invoice date as "2024-01-15"
    And I add line items with total amount "1250.00" EUR
    And I set the VAT rate as "19%" 
    And I save the invoice
    Then the invoice should be created successfully
    And the invoice status should be "unpaid"
    And the total amount should be "1487.50" EUR including VAT

  Scenario: Create a delayed voucher
    Given I am on the invoice management page
    When I create a new invoice with type "delayed voucher"
    And I set the delay period as "30 days"
    And I add line items with total amount "2000.00" EUR
    And I save the invoice
    Then the delayed voucher should be created
    And the payment due date should be "2024-02-14"

  Scenario: Mark invoice as paid
    Given I have an unpaid invoice with number "INV-2024-001"
    And the invoice amount is "1487.50" EUR
    When I mark the invoice as paid
    And I record the payment date as "2024-01-20"
    Then the invoice status should be "paid"
    And the payment date should be recorded as "2024-01-20"

  Scenario: Process billing for stock deliveries
    Given I have stock deliveries for the period "2024-01-01" to "2024-01-31"
    And the deliveries have a total value of "15000.00" EUR
    When I generate billing for the period
    Then a billing record should be created
    And the billing amount should be "15000.00" EUR
    And the billing should include all deliveries from the period

  Scenario: Create financial adjustment
    Given I have an invoice with an error in the amount
    When I create an adjustment with type "credit memo"
    And I set the adjustment amount as "-150.00" EUR
    And I specify the reason as "Calculation error"
    And I save the adjustment
    Then the adjustment should be recorded
    And the net invoice amount should be reduced by "150.00" EUR

  Scenario: Handle VAT calculations
    Given I am creating an invoice
    When I add line items with net amount "1000.00" EUR
    And I apply VAT rate "19%"
    Then the VAT amount should be "190.00" EUR
    And the gross total should be "1190.00" EUR

  Scenario: Manage open items (Offene Posten)
    Given I have several unpaid invoices
    When I generate an open items report
    Then I should see all unpaid invoices listed
    And the total outstanding amount should be calculated
    And invoices should be sorted by due date

  Scenario: Process adjustment items
    Given I have a base adjustment record
    When I add adjustment items with the following details:
      | Description    | Amount  | Type   |
      | Price increase | 100.00  | debit  |
      | Discount       | -50.00  | credit |
      | Service fee    | 25.00   | debit  |
    Then the adjustment should have 3 items
    And the net adjustment amount should be "75.00" EUR

  Scenario: Calculate margin for financial analysis
    Given I have cost entries totaling "8000.00" EUR
    And I have revenue entries totaling "10000.00" EUR
    When I calculate the margin
    Then the margin should be "2000.00" EUR
    And the margin percentage should be "20%"

  Scenario: Handle invoice validation
    Given I am creating a new invoice
    When I try to save an invoice without line items
    Then I should see a validation error "Invoice must have at least one line item"
    And the invoice should not be saved

  Scenario: Track payment transfers (Überweisungen)
    Given I have processed payments totaling "5000.00" EUR
    When I initiate a bank transfer
    And I specify the transfer amount as "5000.00" EUR
    And I set the transfer date as "2024-01-25"
    Then the transfer should be recorded
    And the payment status should be updated to "transferred"