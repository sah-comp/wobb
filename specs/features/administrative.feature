Feature: Administrative Functions
  As a system administrator
  I want to manage users, system configuration, and administrative tasks
  So that I can maintain system security and operational efficiency

  Background:
    Given I am logged into the Wobb system
    And I have administrative privileges

  Scenario: User authentication and session management
    Given I have valid user credentials
    When I log into the system with username "admin" and password "secure123"
    Then I should be authenticated successfully
    And a user session should be established
    And I should have access to administrative functions

  Scenario: Validate user session security
    Given I am logged into the system
    When my session expires after the timeout period
    Then I should be automatically logged out
    And I should be redirected to the login page
    And I should need to re-authenticate to continue

  Scenario: Create and manage user accounts
    Given I am in the user management section
    When I create a new user account with username "operator1"
    And I set the user role as "Operator"
    And I set the password as "temp123"
    And I enable the account
    Then the user account should be created successfully
    And the user should be able to log in with the credentials

  Scenario: Manage user roles and permissions
    Given I have a user account "operator1"
    When I assign the role "Financial Manager" to the user
    Then the user should have permissions for financial operations
    And the user should be able to access invoice management
    But the user should not have access to system configuration

  Scenario: System planning and scheduling
    Given I am in the planning module
    When I create a production plan for "2024-02-01"
    And I set the planned capacity as "500" units
    And I assign staff members to the plan
    And I save the planning information
    Then the production plan should be scheduled
    And staff should be notified of their assignments

  Scenario: Configure system variables (Var)
    Given I am in the system configuration section
    When I update a system variable "max_daily_capacity" to "1000"
    And I update a variable "default_vat_rate" to "19.0"
    And I save the configuration changes
    Then the system variables should be updated
    And the new values should take effect immediately

  Scenario: Manage language settings (multilingual support)
    Given the system supports multiple languages
    When I change the system language to "German"
    Then all interface elements should display in German
    And date formats should follow German conventions
    And currency should display in EUR format

  Scenario: Multi-user environment management
    Given multiple users are accessing the system simultaneously
    When "user1" and "user2" both access the same record
    Then the system should handle concurrent access safely
    And data integrity should be maintained
    And users should see appropriate conflict resolution messages

  Scenario: System backup and maintenance
    Given I need to perform system maintenance
    When I initiate a system backup
    Then all critical data should be backed up
    And the backup should include user data, configurations, and business records
    And the backup should be verified for completeness

  Scenario: Monitor system activity and logging
    Given users are performing various operations
    When I check the system activity logs
    Then I should see login/logout events
    And I should see data modification events
    And I should see error events with appropriate detail
    And logs should include timestamps and user identification

  Scenario: Manage data import/export capabilities
    Given I need to exchange data with external systems
    When I configure a data export to "iQ-Agrar" system
    And I map the required data fields
    And I initiate the export process
    Then the data should be exported in the correct format
    And the export should be confirmed as successful

  Scenario: Handle system errors and exceptions
    Given an unexpected system error occurs
    When the error is caught by the system
    Then the error should be logged with full details
    And the user should see a friendly error message
    And the system should remain stable and recoverable

  Scenario: Configure notification settings
    Given I want to manage system notifications
    When I configure email notifications for "invoice_due"
    And I set the notification frequency as "daily"
    And I add recipients "finance@company.com"
    Then notifications should be sent according to the schedule
    And recipients should receive timely alerts

  Scenario: Manage system integration with external services
    Given the system needs to integrate with external APIs
    When I configure integration credentials for "banking_api"
    And I test the connection
    Then the integration should be established successfully
    And data exchange should work as expected

  Scenario: System performance monitoring
    Given the system is in operation
    When I check system performance metrics
    Then I should see response time statistics
    And I should see database performance indicators
    And I should see user load statistics
    And I should receive alerts if performance degrades

  Scenario: Data archiving and retention policies
    Given the system has accumulated historical data
    When I apply data retention policies
    Then old data should be archived according to rules
    And archived data should remain accessible for compliance
    And active system performance should be maintained