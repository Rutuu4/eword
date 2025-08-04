<?php 
// Configuration options


$config[‘stripe_key_test_public’]         = ”;

$config[‘stripe_key_test_secret’]         = ”;

$config[‘stripe_key_live_public’]         = ”;

$config[‘stripe_key_live_secret’]         = ”;

$config[‘stripe_test_mode’]               = TRUE;

$stripe = new Stripe( $config ); // Create the library object

?>