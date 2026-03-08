<?php
defined('BASEPATH') or exit('No direct script access allowed');
function send_whatsapp_template($to, $value1, $name, $email, $phoneno, $country, $exam, $visa, $course)
{
    $payload = [
        "template" => [
            "components" => [
                [
                    "type" => "BODY",
                    "parameters" => [
                        [
                            "text" => $value1,
                            "type" => "text"
                        ],

                        [
                            "text" => $name,
                            "type" => "text"
                        ],
                        [
                            "text" => $email,
                            "type" => "text"
                        ],
                        [
                            "text" => $phoneno,
                            "type" => "text"
                        ],
                        [
                            "text" => $country,
                            "type" => "text"
                        ],
                        [
                            "text" => $exam,
                            "type" => "text"
                        ],
                        [
                            "text" => $visa,
                            "type" => "text"
                        ],
                        [
                            "text" => $course,
                            "type" => "text"
                        ]
                    ]
                ]
            ],
            "name" => "foreigneducationmou",
            "language" => [
                "code" => "en",
                "policy" => "deterministic"
            ]
        ],
        "to" => $to,
        "type" => "template",
        "templateName" => "foreigneducationmou"
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => "https://api.dovesoft.io/REST/directApi/message",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Key: 3ee1b0be70XX",
            "wabaNumber: 919978291781",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}
