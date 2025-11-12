Feature: Supplier Management
  As a slaughterhouse procurement manager
  I want to manage suppliers, deliverers, and company information
  So that I can maintain accurate supplier records and track deliveries

  Background:
    Given I am logged into the Wobb system
    And I have supplier management permissions

  Scenario: Register a new supplier company
    Given I am on the supplier management page
    When I create a new supplier with name "Bauernhof Schmidt GmbH"
    And I set the company address as "Hauptstraße 123, 12345 Musterstadt"
    And I set the tax ID as "DE123456789"
    And I set the contact email as "info@bauernhof-schmidt.de"
    And I save the supplier information
    Then the supplier should be registered in the system
    And the supplier should have status "active"

  Scenario: Manage deliverer information
    Given I have a supplier "Bauernhof Schmidt GmbH"
    When I add a deliverer "Hans Mueller"
    And I set the deliverer contact number as "+49 123 456789"
    And I assign the deliverer to the supplier
    Then the deliverer should be associated with "Bauernhof Schmidt GmbH"
    And the deliverer should be available for delivery tracking

  Scenario: Track delivery performance
    Given I have a deliverer "Hans Mueller"
    And the deliverer has made deliveries in the past month
    When I review the delivery performance
    Then I should see the number of deliveries made
    And I should see the total volume delivered
    And I should see the on-time delivery percentage

  Scenario: Update supplier contact information
    Given I have an existing supplier "Bauernhof Schmidt GmbH"
    When I update the contact email to "new-email@bauernhof-schmidt.de"
    And I update the phone number to "+49 987 654321"
    And I save the changes
    Then the supplier contact information should be updated
    And the changes should be reflected in all related records

  Scenario: Manage supplier status
    Given I have an active supplier "Bauernhof Schmidt GmbH"
    When I change the supplier status to "inactive"
    And I specify the reason as "Contract terminated"
    Then the supplier status should be "inactive"
    And the supplier should not appear in active supplier lists
    But the historical data should remain accessible

  Scenario: Track supplier conditions and contracts
    Given I have a supplier "Bauernhof Schmidt GmbH"
    When I add a condition with type "price per kg"
    And I set the condition value as "3.50" EUR
    And I set the condition validity period from "2024-01-01" to "2024-12-31"
    Then the condition should be applied to deliveries in the specified period
    And the condition should affect invoice calculations

  Scenario: Handle condition types for stock and weight
    Given I have a supplier with conditions
    When I add a condition "stockperitem" with value "5.00" EUR
    And I add a condition "stockperweight" with value "0.50" EUR per kg
    Then both conditions should be available for billing calculations
    And the appropriate condition should be applied based on billing type

  Scenario: Manage company master data (Stammdaten)
    Given I am managing company information
    When I create a company record for "Fleischverarbeitung Nord AG"
    And I set the business type as "Processor"
    And I set the annual volume capacity as "50000" tons
    And I save the company data
    Then the company should be registered in the master data
    And the company should be available for business relationships

  Scenario: Track supplier payment terms
    Given I have a supplier "Bauernhof Schmidt GmbH"
    When I set payment terms as "Net 30 days"
    And I set the discount terms as "2% within 10 days"
    Then the payment terms should be applied to all invoices
    And early payment discounts should be calculated automatically

  Scenario: Validate supplier data completeness
    Given I am creating a new supplier
    When I try to save the supplier without a name
    Then I should see a validation error "Supplier name is required"
    And the supplier should not be saved
    When I try to save with an invalid tax ID format
    Then I should see a validation error "Invalid tax ID format"

  Scenario: Generate supplier performance reports
    Given I have multiple suppliers with delivery history
    When I generate a supplier performance report for "Q1 2024"
    Then I should see delivery volume by supplier
    And I should see quality ratings by supplier
    And I should see payment behavior by supplier
    And I should see the top performing suppliers ranked

  Scenario: Manage supplier categories
    Given I have suppliers in the system
    When I categorize "Bauernhof Schmidt GmbH" as "Premium Supplier"
    And I categorize "Kleinbetrieb Mueller" as "Standard Supplier"
    Then suppliers should be grouped by category
    And different business rules should apply per category