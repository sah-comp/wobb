# Wobb Specifications

This directory contains Gherkin language specifications for the Wobb slaughterhouse management system.

## Structure

- `features/` - Gherkin feature files organized by business domain
  - `livestock-management.feature` - Stock, Quality, Piggery operations
  - `financial-operations.feature` - Invoice, Billing, Adjustments  
  - `supplier-management.feature` - Suppliers, Deliverers, Companies
  - `analysis-reporting.feature` - Statistics, Analysis, Reporting
  - `administrative.feature` - User management, Planning, Configuration

## Gherkin Syntax

The specifications follow standard Gherkin syntax:
- **Feature**: High-level business functionality
- **Scenario**: Specific test case
- **Given**: Initial state/preconditions
- **When**: Action/event
- **Then**: Expected outcome
- **And/But**: Additional steps

## Language

Specifications use English with German business terminology where appropriate to match the existing codebase.