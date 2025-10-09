<?php

use Twilio\Rest\Client;

class Twilio_lib
{
    private $client;
    private $from;

    public function __construct()
    {
        $sid   = "YOUR_TWILIO_SID";
        $token = "YOUR_TWILIO_AUTH_TOKEN";
        $this->from = "whatsapp:+14155238886"; // Twilio Sandbox Sender
        $this->client = new Client($sid, $token);
    }

    // Updated function with message parameter
    public function send_project_message($to, $name, $domain, $customMessage = null)
    {
        // Build default message if none is passed
        $messageText = $customMessage ?? (
            "Hello $name 👋,\n\n"
            . "Thank you for applying for a Project/Internship in *$domain*.\n"
            . "We will review your application and get back to you soon.\n\n"
            . "– Team Eword"
        );

        try {
            $this->client->messages->create(
                "whatsapp:" . $to,
                [
                    "from" => $this->from,
                    "body" => $messageText
                ]
            );
            return true;
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp send failed: ' . $e->getMessage());
            return false;
        }
    }
}