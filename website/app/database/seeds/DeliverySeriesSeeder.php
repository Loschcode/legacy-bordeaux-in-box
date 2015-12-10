<?php

class DeliverySeriesSeeder extends Seeder {

    public function run()
    {

    	// Months forwards to generate
    	$forwards = 20;

    	Eloquent::unguard();
        DB::table('delivery_series')->delete();

        $this->command->info("Generating delivery series ...");

        $num = 1;

        while ($num < $forwards) {

            $increment = date("Y-m-d", strtotime("+".$num." month", time()));
            $counter = 50*$num;

            $delivery_serie = new DeliverySerie;
            $delivery_serie->delivery = $increment;
            $delivery_serie->goal = $counter;
            $delivery_serie->save();

            $num++;

        }


        $this->command->info("Delivery series creation done !");

    }

}