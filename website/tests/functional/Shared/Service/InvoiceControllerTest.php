<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Customer;
use App\Models\CustomerProfile;

class Shared_Service_InvoiceControllerTest extends TestCase
{

  use DatabaseTransactions;

  /** @test */
  public function subscribed_customer_got_invoice_callback_from_stripe()
  {

    $customer = factory(Customer::class)->create();
    $this->post('POST', 'service/api/box-question-customer-answer');

  }

  public function fakeCallback()
  {

    return '{
            "id": "ch_17H02AJ1e9gLDryLY2BqvH4v",
            "object": "charge",
            "amount": 2490,
            "amount_refunded": 0,
            "application_fee": null,
            "balance_transaction": "txn_17GyKTJ1e9gLDryLZqtwOIwi",
            "captured": true,
            "created": 1449875442,
            "currency": "eur",
            "customer": "cus_7W26GtbzmFaPZp",
            "description": "Paiement utilisateur `ges.jeremie@gmail.com` (ID `1` / ORDER `4`)",
            "destination": null,
            "dispute": null,
            "failure_code": null,
            "failure_message": null,
            "fraud_details": {
            },
            "invoice": null,
            "livemode": false,
            "metadata": {
              "user_id": "1",
              "order_id": "4"
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
              "id": "card_17H023J1e9gLDryLpu5LGMz2",
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
              "customer": "cus_7W26GtbzmFaPZp",
              "cvc_check": "pass",
              "dynamic_last4": null,
              "exp_month": 2,
              "exp_year": 2017,
              "funding": "credit",
              "last4": "4242",
              "metadata": {
              },
              "name": "ges.jeremie@gmail.com",
              "tokenization_method": null
            },
            "statement_descriptor": null,
            "status": "succeeded"
          }';

  }

}
