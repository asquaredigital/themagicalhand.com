<?php
require '../vendor/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = array('message' => 'Invalid request.');
    echo json_encode($response);
    exit;
}

$config = require '../vendor/config.php';

$awsKey    = $config['aws']['key'];
$awsSecret = $config['aws']['secret'];
$awsRegion = $config['aws']['region'];

$sesClient = new SesClient([
    'version'     => 'latest',
    'region'      => $awsRegion,
    'credentials' => [
        'key'    => $awsKey,
        'secret' => $awsSecret,
    ],
]);

// Get form data
$u_name   = $_POST['name'];
$u_email  = $_POST['email'];
$p_number = $_POST['contact'];
$msg      = $_POST['message'];

// Email content
$emailSubject = 'Enquiry from Website';
$emailBody    = "Name: $u_name\nEmail: $u_email\nPhone Number: $p_number\nMessage: $msg";

$senderEmail    = 'themagicalhandwebsite@gmail.com';
$recipientEmail = 'elavarasan5193@gmail.com';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $result = $sesClient->sendEmail([
        'Destination' => [
            'ToAddresses' => [$recipientEmail],
        ],
        'Message' => [
            'Body' => [
                'Text' => [
                    'Charset' => 'UTF-8',
                    'Data'    => $emailBody,
                ],
            ],
            'Subject' => [
                'Charset' => 'UTF-8',
                'Data'    => $emailSubject,
            ],
        ],
        'Source'          => $senderEmail,
        'ReplyToAddresses' => [$u_email],
    ]);

    $response = ['message' => 'Email sent successfully!', 'messageId' => $result['MessageId']];
    echo json_encode($response);

} catch (AwsException $e) {
    $response = ['message' => 'Failed to send email.', 'error' => $e->getAwsErrorMessage()];
    echo json_encode($response);
}
?>