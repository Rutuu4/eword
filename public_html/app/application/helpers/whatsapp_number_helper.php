<?php
defined('BASEPATH') or exit('No direct script access allowed');
function format_whatsapp_number($number)
{
    $number = trim($number);

    if (strpos($number, '+91') === 0) {
        return $number; // already correct
    }

    if (strpos($number, '91') === 0) {
        return '+' . $number; // add +
    }

    return '+91' . $number; // add country code
}
