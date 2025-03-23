<?php

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Twilio\Rest\Client;

require __DIR__ . "/libs/infobip.php";
require __DIR__ . "/libs/twilio.php";


// Check if the request is an API call
if (isset($_GET['api']) && $_GET['api'] === 'send_sms') {
    header('Content-Type: application/json');
    
    $number = $_POST["number"];
    $message = $_POST["message"];
    
    if ($_POST["provider"] === "infobip") {
        // Infobip SMS sending logic
        $base_url = "https://your-base-url.api.infobip.com";
        $api_key = "your API key";

        $configuration = new Configuration($base_url, $api_key);

        $api = new SmsApi(config: $configuration);
        $destination = new SmsDestination(to: $number);
        $message = new SmsTextualMessage(
            destinations: [$destination],
            text: $message,
            from: "Jairo"
        );
        $request = new SmsAdvancedTextualRequest(messages: [$message]);
        $response = $api->sendSmsMessage($request);
        
        echo json_encode(["status" => "success", "message" => "Message sent."]);
        exit();
    } else if ($_POST["provider"] === "email") {   // Email
        $to = $number; // Use the number field as the email address
        $subject = "New Message";
        $headers = "From: your-email@example.com"; // Replace with your email
        $body = $message;

        if (mail($to, $subject, $body, $headers)) {
            echo json_encode(["status" => "success", "message" => "Email sent."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send email."]);
        }
        exit();
    } else {   // Twilio

        $account_id = "AC88d81cf378fa19078a2a99c8bc7fcf15";
        $auth_token = "de232cf07c1f6a5f5d5d3f43f6cc20b3";

        $client = new Client($account_id, $auth_token);
        $twilio_number = "+ 09926265229";

        $client->messages->create(
            $number,
            [
                "from" => $twilio_number,
                "body" => $message
            ]
        );

        echo json_encode(["status" => "success", "message" => "Message sent."]);
        exit();
    }
    
    echo json_encode(["status" => "error", "message" => "Failed to send message."]);
    exit();
}
