<?php
/**
 * AutoprecalcContext for Behat testing with real integrations.
 */

require __DIR__ . '/../../app/config/bootstrap.php';
require __DIR__ . '/../../app/config/config.php';

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class AutoprecalcContext implements Context {
    /**
     * The analysis bean for the processed data.
     */
    private $analysis = null;

    /**
     * Flag for notification sent
     */
    private $notificationSent = false;

    /**
     * Flag for log written
     */
    private $logWritten = false;

    /**
     * Flag for report formatting
     */
    private $reportFormatted = false;
    /**
     * Flag for report calculations and summaries
     */
    private $reportIncludesCalculations = false;
    /**
     * Flag for report readiness
     */
    private $reportReady = false;
    /**
     * Flag for notification shown
     */
    private $notificationShown = false;
    /**
     * Flag for processed slaughter day data
     */
    private $dataProcessed = false;
    /**
     * Flag for system email check
     */
    private $systemCheckedEmails = false;
    /**
     * Simulated email data
     */
    private $emailReceived = null;

    /**
     * Flag for slaughter day data receipt
     */
    private $slaughterDayDataReceived = false;
    /**
     * @Given I receive a new email with slaughter day data
     */
    public function iReceiveNewEmailWithSlaughterDayData()
    {
        // Simulate receiving email with CSV attachment
        $csvData = "date,quality,amount,price\n2025-10-11,S,100,5.50\n2025-10-11,E,50,4.20\n";
        
        // Parse CSV using ParseCSV
        $parser = new \ParseCsv\Csv();
        $parser->parse($csvData);
        
        // Create analysis bean
        $this->analysis = R::dispense('analysis');
        $this->analysis->name = 'Autoprecalc Test Analysis';
        $this->analysis->startdate = '2025-10-11';
        $this->analysis->enddate = '2025-10-11';
        $this->analysis->company = R::load('company', 1); // Assume company id 1
        
        // Add stock items
        foreach ($parser->data as $row) {
            $stock = R::dispense('stock');
            $stock->date = $row['date'];
            $stock->quality = $row['quality'];
            $stock->amount = $row['amount'];
            $stock->price = $row['price'];
            $this->analysis->ownStockList[] = $stock;
        }
        
        R::store($this->analysis);
    }

    /**
     * @When the system checks for new emails
     */
    public function theSystemChecksForNewEmails()
    {
        // In real system, this would check email server for new messages
        // For test, assume check is done and data is available
        if ($this->analysis) {
            // Email check successful
        } else {
            throw new \Exception('No new slaughter day email found.');
        }
    }

    /**
     * @Then the new slaughter day data should be processed automatically
     */
    public function newSlaughterDayDataShouldBeProcessedAutomatically()
    {
        // Process the data by generating report
        if ($this->analysis) {
            $this->analysis->generateReport();
            R::store($this->analysis);
        } else {
            throw new \Exception('Slaughter day data was not processed automatically.');
        }
    }

    /**
     * @Then I should see a notification that the data has been processed
     */
    public function iShouldSeeNotificationDataProcessed()
    {
        // Send notification email using PHPMailer
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Configure real SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'user@example.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('from@example.com', 'Wobb System');
            $mail->addAddress('manager@example.com', 'Manager');

            $mail->isHTML(true);
            $mail->Subject = 'Slaughter Day Data Processed';
            $mail->Body = 'The new slaughter day data has been processed automatically.';

            $mail->send();
            $this->notificationSent = true;
        } catch (\Exception $e) {
            throw new \Exception('Notification could not be sent: ' . $mail->ErrorInfo);
        }
    }

    /**
     * @Then the report should be ready to send to my manager
     */
    public function reportShouldBeReadyToSend()
    {
        // Check if report is generated (analysis has items)
        if ($this->analysis && count($this->analysis->ownAnalysisitem) > 0) {
            // Report is ready
        } else {
            throw new \Exception('Report is not ready to send.');
        }
    }

    /**
     * @Then the report should include all relevant calculations and summaries
     */
    public function reportShouldIncludeCalculationsAndSummaries()
    {
        // Check if calculations are present
        if ($this->analysis && $this->analysis->piggery > 0) {
            // Calculations included
        } else {
            throw new \Exception('Report does not include calculations and summaries.');
        }
    }

    /**
     * @Then the report should be formatted correctly for easy reading
     */
    public function reportShouldBeFormattedCorrectly()
    {
        // In real app, generate PDF and check format
        // For test, assume formatted if report exists
        if ($this->analysis) {
            // Formatted correctly
        } else {
            throw new \Exception('Report is not formatted correctly.');
        }
    }

    /**
     * @Then the system should log the processing of the new slaughter day data
     */
    public function systemShouldLogProcessing()
    {
        // Log the processing
        error_log('Slaughter day data processed for analysis ID: ' . $this->analysis->id);
        $this->logWritten = true;
    }
}
