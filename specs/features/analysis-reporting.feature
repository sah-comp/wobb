Feature: Analysis and Reporting
  As a slaughterhouse manager
  I want to generate business analysis and statistical reports
  So that I can make informed business decisions and meet regulatory requirements

  Background:
    Given I am logged into the Wobb system
    And I have analysis and reporting permissions

  Scenario: Generate analysis for company internal statistics
    Given I have stock data for the period "2024-01-01" to "2024-01-31"
    And I have supplier data for the same period
    When I generate an internal analysis report
    Then the analysis should include stock summary by quality grades
    And the analysis should include supplier performance metrics
    And the analysis should include damage analysis by codes
    And the analysis should calculate total volumes and values

  Scenario: Create analysis with quality breakdown
    Given I have stock entries with various quality grades
    When I generate an analysis for qualities "S, E, U, R, O, P, Z"
    Then the analysis should show count and weight for each quality
    And the analysis should calculate average prices per quality
    And the analysis should show quality distribution percentages

  Scenario: Analyze supplier performance over time period
    Given I have deliveries from multiple suppliers in "2024-01"
    When I generate a supplier analysis
    Then I should see analysis items for each supplier
    And each supplier analysis should include total deliveries
    And each supplier analysis should include average quality scores
    And each supplier analysis should include financial totals

  Scenario: Generate damage analysis by supplier
    Given I have stock with damage codes from various suppliers
    When I generate a damage analysis report
    Then I should see damage statistics by damage code "01" to "10"
    And I should see which suppliers have the highest damage rates
    And I should see financial impact of damages by supplier

  Scenario: Create statistical reports (Statistiken)
    Given I have operational data for "Q1 2024"
    When I generate statistical reports
    Then I should see volume statistics by month
    And I should see quality distribution trends
    And I should see supplier performance trends
    And I should see financial performance indicators

  Scenario: Generate reports for arbitrary time periods
    Given I have historical data spanning multiple years
    When I select a custom time period from "2023-06-01" to "2024-02-29"
    And I generate analysis for this period
    Then the analysis should include only data from the specified period
    And the analysis should show period-specific metrics
    And the analysis should allow comparison with other periods

  Scenario: Landesamt reporting via email
    Given I have completed analysis for the reporting period
    When I generate a Landesamt report
    And I configure the email recipient as "landesamt@example.de"
    And I send the report via email
    Then the report should be sent successfully
    And the email should contain properly formatted data
    And a delivery confirmation should be recorded

  Scenario: Cost analysis and margin calculation
    Given I have cost data and revenue data for analysis
    When I generate a cost analysis report
    Then I should see total costs by category
    And I should see revenue by source
    And I should see calculated margins and profitability
    And I should see cost per unit metrics

  Scenario: Compare analysis periods
    Given I have analysis data for "2024-01" and "2023-01"
    When I create a comparison analysis
    Then I should see side-by-side metrics for both periods
    And I should see percentage changes between periods
    And I should see trending indicators (up/down/stable)
    And I should see variance analysis for key metrics

  Scenario: Export analysis data for external systems
    Given I have generated an analysis report
    When I export the analysis data to CSV format
    Then the exported file should contain all analysis items
    And the file should include headers for all data columns
    And the data should be properly formatted for import

  Scenario: Validate analysis data integrity
    Given I am generating an analysis report
    When the system encounters missing or invalid data
    Then I should see warnings about data quality issues
    And the analysis should continue with available data
    And data gaps should be clearly marked in the report

  Scenario: Schedule automated analysis generation
    Given I want regular analysis reports
    When I configure automated analysis for "monthly"
    And I set the recipients as "management@company.com"
    Then the system should generate analysis reports monthly
    And the reports should be automatically sent via email
    And I should receive confirmation of successful delivery

  Scenario: Generate analysis with applied conditions
    Given I have suppliers with various contract conditions
    When I generate analysis including condition calculations
    Then condition-based adjustments should be applied
    And I should see the impact of "stockperitem" conditions
    And I should see the impact of "stockperweight" conditions
    And the total financial impact should be calculated

  Scenario: Create piggery-specific analysis
    Given I have piggery operations data
    When I generate a piggery analysis report
    Then I should see analysis by piggery operation
    And I should see stock counts per piggery
    And I should see quality distributions per piggery
    And I should see financial performance per piggery