<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\BaseController;

class StripeController extends BaseController
{
	public function index(){
		//
	}

	public function getSecretKey(){

		$app = new \Slim\App();

		$app->post("/", function (Request $request, Response $response) {
			// Use an existing Customer ID if this is a returning customer.
			return $response->write("hello");
		  });
	}

	public function getClientSecret(){

		// Set your secret key. Remember to switch to your live secret key in production.
		// See your keys here: https://dashboard.stripe.com/apikeys
		\Stripe\Stripe::setApiKey($this->secret_key);

		$intent = \Stripe\PaymentIntent::create([
			'amount' => 1099,
			'currency' => 'inr',
		]);

		return $intent->client_secret;
		// Pass the client secret to the client
	}
}
