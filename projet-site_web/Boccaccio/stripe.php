<?php
// stripe.php
// Initialise Stripe avec ta clé secrète

require_once('vendor/autoload.php'); // charge Stripe via Composer

$stripe = new \Stripe\StripeClient('Clé_api'); 
