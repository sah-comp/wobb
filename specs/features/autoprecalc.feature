Feature: Autoprecalc

	As a daemon user
	I want to process a slaughter day automatically
	So that I can send a report to my manager without manual interaction

	Scenario: Automatically check emails for new slaughter day data
		Given I receive a new email with slaughter day data
		When the system checks for new emails
		Then the new slaughter day data should be processed automatically
		And I should see a notification that the data has been processed
		And the report should be ready to send to my manager
		And the report should include all relevant calculations and summaries
		And the report should be formatted correctly for easy reading
		And the system should log the processing of the new slaughter day data
		