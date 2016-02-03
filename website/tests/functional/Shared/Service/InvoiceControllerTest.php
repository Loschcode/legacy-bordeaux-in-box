<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\CustomerPaymentProfile;
use App\Models\CustomerOrderPreference;
use App\Libraries\Payments;

class Shared_Service_InvoiceControllerTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function subscribed_customer_got_invoice_callback_from_stripe()
  {

    $stripe_charge = 'ch_'. str_random(8);
    $stripe_card = 'card_' . str_random(8);
    $stripe_balance_transaction = 'blx_' . str_random(8);
    $amount_in_cents = 2490;

    $customer_payment_profile = factory(CustomerPaymentProfile::class)->create([

      'stripe_card' => $stripe_card,
      'stripe_plan' => 'plan2490'

      ]);

    $customer_profile = $customer_payment_profile->profile()->first();
    $customer = $customer_profile->customer()->first();

    $customer_order_preference = factory(CustomerOrderPreference::class)->create([

        'customer_profile_id' => $customer_profile->id,
        'stripe_plan' => 'plan2490',

      ]);

    $this->post('shared/service/invoices', ['webhook' => $this->fakeCallback($customer, $customer_profile, $customer_payment_profile->stripe_customer, $stripe_charge, $stripe_card, $stripe_balance_transaction, $amount_in_cents)]);

    $this->assertResponseOk();



  }

  public function fakeCallback($customer, $customer_profile, $stripe_customer, $stripe_charge, $stripe_card, $stripe_balance_transaction, $amount_in_cents)
  {

    return '

    {
    "created": 1326853478,
    "livemode": false,
    "id": "evt_'.rand(0,10000000).'",
    "type": "charge.succeeded",
    "object": "event",
    "request": null,
    "pending_webhooks": 1,
    "api_version": "2014-09-08",
    "data": {
      "object":
            {
            "id": "'.$stripe_charge.'",
            "object": "charge",
            "amount": '.$amount_in_cents.',
            "amount_refunded": 0,
            "application_fee": null,
            "balance_transaction": "'.$stripe_balance_transaction.'",
            "captured": true,
            "created": 1449875442,
            "currency": "eur",
            "customer": "'.$stripe_customer.'",
            "description": "Testing mode",
            "destination": null,
            "dispute": null,
            "failure_code": null,
            "failure_message": null,
            "fraud_details": {
            },
            "invoice": null,
            "livemode": false,
            "metadata": {
              "customer_id": "'.$customer->id.'",
              "customer_profile_id": "'.$customer_profile->id.'",
              "payment_type": "direct_invoices"
            },
            "order": null,
            "paid": true,
            "receipt_email": null,
            "receipt_number": null,
            "refunded": false,
            "refunds": {
              "object": "list",
              "data": [

              ],
              "has_more": false,
              "total_count": 0,
              "url": "/v1/charges/ch_17H02AJ1e9gLDryLY2BqvH4v/refunds"
            },
            "shipping": null,
            "source": {
              "id": "'.$stripe_card.'",
              "object": "card",
              "address_city": null,
              "address_country": null,
              "address_line1": null,
              "address_line1_check": null,
              "address_line2": null,
              "address_state": null,
              "address_zip": null,
              "address_zip_check": null,
              "brand": "Visa",
              "country": "US",
              "customer": "'.$stripe_customer.'",
              "cvc_check": "pass",
              "dynamic_last4": null,
              "exp_month": 2,
              "exp_year": 2017,
              "funding": "credit",
              "last4": "4242",
              "metadata": {
              },
              "name": "'.$customer->email.'",
              "tokenization_method": null
            },
            "statement_descriptor": null,
            "status": "succeeded"
          }

    }

  }';

  }

}
