<?php

class OrdersSeeder extends Seeder {

    public function run()
    {

    	// orders to generate
    	$orders = 50;
        $only_admin = FALSE; // We will focus to generate orders and payments for the main admin

    	Eloquent::unguard();
        DB::table('orders')->delete();

        $this->command->info("Generating orders & fake payments ...");

        $num = 0;

        while ($num < $orders) {

            $order = new Order;

            if ($only_admin) $profile = User::where('email', 'admin@admin.com')->first()->profiles()->first();
            else $profile = UserProfile::orderBy(DB::raw('RAND()'))->first();

            $user = $profile->user()->first();
            $delivery_serie = DeliverySerie::orderBy(DB::raw('RAND()'))->first();

            $order->user_profile()->associate($profile);
            $order->user()->associate($user);
            $order->delivery_serie()->associate($delivery_serie);

            $take_away = array_random([TRUE, FALSE]);

            if ($take_away) {

                $delivery_spot = DeliverySpot::orderBy(DB::raw('RAND()'))->first();
                $order->delivery_spot()->associate($delivery_spot);
                $order->take_away = TRUE;
                $order->unity_and_fees_price = array_random([19.90, 21.90]);

            } else {

                $order->take_away = FALSE;
                $order->unity_and_fees_price = array_random([21.40, 26.40, 23.40, 28.40]);

            }

            $order->gift = array_random([TRUE, FALSE]);
            $order->locked = FALSE;

            $order->status = array_random(['paid', 'canceled', 'unpaid', 'failed', 'half-paid', 'scheduled', 'ready', 'problem', 'delivered', 'packing']);

            if ($order->status === 'delivering') {

                $order->locked = TRUE;

            }

            if ($order->status === 'delivered') {

                $num = rand(3, 150);
                $order->date_completed = date("Y-m-d", strtotime("+".$num." day", time()));

            }

            if ($order->status != 'scheduled') {

                $payment = $this->make_payment($user, $profile, $order->unity_and_fees_price);
                $order->payment()->associate($payment);

            }

            $order->save();
            
            $num++;

        }

        $this->command->info("Delivery orders creation done !");

    }

    public function make_payment($user, $profile, $amount)
    {

        $payment = new Payment;
        $payment->user()->associate($user);
        $payment->profile()->associate($profile);

        $payment->stripe_event = 'ch_'.rand(0,10000).'4g4GvIIyezb'.rand(0,10000).'ziuhCN'.rand(0,10000).'YaiH';

        $stripe_customer = 'cus_'.rand(0,1000).'piq'.rand(0,10000).'cgrMIIG'.rand(0,10000).'X';
        $profile->stripe_customer = $stripe_customer;
        $profile->save();

        $payment->stripe_customer = $stripe_customer;
        $payment->stripe_charge = rand(0,1000000000);
        $payment->stripe_card = rand(0,10000000);
        $payment->type = array_random(['direct_invoice', 'plan']);
        $payment->paid = array_random([TRUE, FALSE]);
        $payment->last4 = rand(1000, 9999);
        $payment->amount = $amount;

        $payment->save();

        $payment->bill_id = strtoupper(str_random(1)) . rand(100,999) . $user->id . $payment->id;
        $payment->save();

        return $payment;

    }



}