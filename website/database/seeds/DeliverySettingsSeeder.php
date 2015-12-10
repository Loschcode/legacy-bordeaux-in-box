<?php

class DeliverySettingsSeeder extends Seeder {

    public function run()
    {

    	// Months forwards to generate
    	$forwards = 20;

    	Eloquent::unguard();
        DB::table('delivery_settings')->delete();

        $this->command->info("Generating delivery settings ...");

        $delivery_setting = new DeliverySetting;
        $delivery_setting->regional_delivery_fees = 1.50;
        $delivery_setting->national_delivery_fees = 6.50;
        $delivery_setting->save();

        $this->command->info("Delivery settings creation done !");

    }

}