<?php

namespace App\Controllers;

class Home extends BaseController
{
	/**
	 * Working api
	 */
    protected function chargeAmountFromCard(array $details)
    {
        $customerDetailsAry = [
            'email' => $details['email'],
            'source' => $details['token']
		];

        $customerResult = $this->addCustomer($customerDetailsAry);

        $charge = new \Stripe\Charge();

        $cardDetailsAry = [
            'customer' => $customerResult->id,
            'amount' => $details['amount'] * 100 ,
            'currency' => $details['currency_code'],
            'description' => $details['item_name'],
            'metadata' => [
                'order_id' => $details['item_number']
			],
        ];


        $result = $charge->create($cardDetailsAry);

        return $result->jsonSerialize();
    }

	public function payWithStripe(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$data = $stripe->tokens->create([
			'card' => [
			'number' => '4242424242424242',
			'exp_month' => 9,
			'exp_year' => 2022,
			'cvc' => '314',
			],
		]);

		/*echo $data->id;
		echo "<pre>";
		print_r($data);*/


		$response = $this->chargeAmountFromCard(["email" => "jas@gmail.com", "token" => $data->id, "amount" => 1000, "currency_code" => "inr", "item_name" => "Web development test", "item_number" => "AJ00125"]);
		
		$amount = $response["amount"] / 100;
		
		$insert_data = [
			"jas@gmail.com",
			"AJ00125",
			$amount,
			$response["currency"],
			$response["balance_transaction"],
			$response["status"],
			json_encode($response)
		];
		
		echo "<pre>";
		print_r($insert_data);
		echo "</pre>";

	}
	/**
	 * Working api
	 */


	public function index()
	{
		// Set your secret key. Remember to switch to your live secret key in production.
		// See your keys here: https://dashboard.stripe.com/apikeys
		\Stripe\Stripe::setApiKey($this->secret_key);

		$customer = \Stripe\PaymentIntent::create([
			'description' => 'Software development services',
			'shipping' => [
			  'name' => 'Jenny Rosen',
			  'address' => [
				'line1' => '510 Townsend St',
				'postal_code' => '98140',
				'city' => 'San Francisco',
				'state' => 'CA',
				'country' => 'US',
			  ],
			],
			'amount' => 1099,
			'currency' => 'inr',
			'payment_method_types' => ['card'],
		  ]);

		echo "<pre>";
		print_r($customer);


	}

	/**
	 * WORKING WEB
	 */
	public function payNowWeb(){

		\Stripe\Stripe::setApiKey($this->secret_key);

		header('Content-Type: application/json');

		try {
		// retrieve JSON from POST body
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$paymentIntent = \Stripe\PaymentIntent::create([
			'amount' => $this->calculateOrderAmount($json_obj->items),
			'currency' => 'inr',
		]);

		$output = [
			'clientSecret' => $paymentIntent->client_secret,
		];

		echo json_encode($output);
		} catch (Error $e) {
		http_response_code(500);
		echo json_encode(['error' => $e->getMessage()]);
		}
	}

	public function checkout(){

		$data = [
			"key"	=>	$this->secret_key,
		];

		return view("checkout", $data);
	}
	/**
	 * WORKING WEB
	 */

	 

	public function createAccountLink(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$account_links = \Stripe\AccountLink::create([
			'account' => 'acct_1032D82eZvKYlo2C',
			'refresh_url' => 'https://example.com/reauth',
			'return_url' => 'https://example.com/return',
			'type' => 'account_onboarding',
		]);
	}


	public function createClientAccount(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->create([
			'type' => 'custom',
			'country' => 'US',
			'email' => 'jenny.rosen@example.com',
			'capabilities' => [
				'card_payments' => ['requested' => true],
				'transfers' => ['requested' => true],
				/**
				 * Optional
				 * capabilities.acss_debit_payments
				 * capabilities.afterpay_clearpay_payments
				 * capabilities.au_becs_debit_payments
				 * capabilities.bacs_debit_payments
				 * capabilities.bancontact_payments
				 * capabilities.boleto_payments
				 * capabilities.card_issuing
				 * capabilities.card_payments
				 * capabilities.cartes_bancaires_payments
				 * capabilities.eps_payments
				 * 
				 * Many more
				 */
			],
		]);
	}

	public function retrieveAccount(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->retrieve(
			'acct_1032D82eZvKYlo2C',
			[]
		);
	}


	public function createClient(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->create([
				'type' => 'custom',
				'country' => 'US',
				'email' => 'jenny.rosen@example.com',
				'capabilities' => [
				'card_payments' => ['requested' => true],
				'transfers' => ['requested' => true],
			],
		]);
	}

	public function updateAccount(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->update(
		'acct_1032D82eZvKYlo2C',
		['metadata' => ['order_id' => '6735']]
		);
	}

	public function deleteClientAccount(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->delete(
			'acct_1032D82eZvKYlo2C',
			[]
		);

	}

	public function rejectClientAccount(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->reject(
			'acct_1032D82eZvKYlo2C',
			['reason' => 'fraud']
		);

	}

	public function viewClient(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->all(['limit' => 3]);
	}


	public function createLoginLink(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$stripe->accounts->createLoginLink(
		'acct_1032D82eZvKYlo2C',
		[]
		);
	}



	public function serverSide(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		$payment_intent = \Stripe\PaymentIntent::create([
		'payment_method_types' => ['card'],
		'amount' => 1000,
		'currency' => 'inr',
		'application_fee_amount' => 123,
		'transfer_data' => [
			'destination' => '{{CONNECTED_STRIPE_ACCOUNT_ID}}',
		],
		]);

		$client_secret = $intent->client_secret;
		// Pass the client secret to the client

	}

	public function fulfilment(){

		$stripe = new \Stripe\StripeClient($this->secret_key);

		// If you are testing your webhook locally with the Stripe CLI you
		// can find the endpoint's secret by running `stripe listen`
		// Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
		$endpoint_secret = 'whsec_...';

		$this->app->post('/webhook', function ($request, $response, $next) {

		$payload = $request->getBody();

		$sig_header = $request->getHeaderLine('stripe-signature');

		$event = null;

		// Verify webhook signature and extract the event.
		// See https://stripe.com/docs/webhooks/signatures for more information.
		try {
			$event = \Stripe\Webhook::constructEvent(
			$payload, $sig_header, $endpoint_secret
			);
		} catch(\UnexpectedValueException $e) {
			// Invalid payload.
			return $response->withStatus(400);
		} catch(\Stripe\Exception\SignatureVerificationException $e) {
			// Invalid Signature.
			return $response->withStatus(400);
		}

		if ($event->type == 'payment_intent.succeeded') {
			$paymentIntent = $event->data->object;
			handleSuccessfulPaymentIntent($paymentIntent);
		}

		return $response->withStatus(200);
		});

		function handleSuccessfulPaymentIntent($paymentIntent) {
		// Fulfill the purchase.
		echo $paymentIntent;
		};

		$app->run();

	}
	

}
