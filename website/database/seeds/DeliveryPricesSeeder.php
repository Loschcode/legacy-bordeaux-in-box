<?php

class DeliveryPricesSeeder extends Seeder {

    public function run()
    {

    	// Months forwards to generate
    	$classic_frequency_prices = [

            '1' => '21.90', // plan + price
            '6' => '19.90',
            '12' => '19.90'

        ];

        $gift_frequency_prices = [

            '1' => '21.90',
            '3' => '64.90',
            '5' => '99.90'

        ];

    	Eloquent::unguard();
        DB::table('delivery_prices')->delete();

        $this->command->info("Generating delivery prices ...");

        foreach ($classic_frequency_prices as $frequency => $price) {

            $delivery_price = new DeliveryPrice;
            $delivery_price->gift = FALSE;
            $delivery_price->frequency = $frequency;
            $delivery_price->unity_price = $price;
            $delivery_price->save();

        }

        foreach ($gift_frequency_prices as $frequency => $price) {

            $delivery_price = new DeliveryPrice;
            $delivery_price->gift = TRUE;
            $delivery_price->frequency = $frequency;
            $delivery_price->unity_price = $price;
            $delivery_price->save();

        }

        $this->command->info("Delivery prices creation done !");

    }

}