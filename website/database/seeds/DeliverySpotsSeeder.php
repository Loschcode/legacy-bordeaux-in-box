<?php

class DeliverySpotsSeeder extends Seeder {

    public function run()
    {

    	// Spots to generate
    	$spots = 5;

    	Eloquent::unguard();
        DB::table('delivery_spots')->delete();

        $this->command->info("Generating delivery spots ...");

        $num = 0;

        while ($num < $spots) {

            $spot = new DeliverySpot;
            $spot->name = array_random(['Ma super post', 'Mon magasin génial', 'Le local inconnu', 'Yo nigga']);
            $spot->city = array_random(['Bordeaux', 'Marseille', 'Paris']);
            $spot->zip = array_random(['33470', '33000', '33800']);
            $spot->address = array_random(['33 rue mon cul', '10 rua da palmeira', '9 rue de la chaise longue', '10 rue de laurent']);
            $spot->save();

            $num++;

        }


        $this->command->info("Delivery spots creation done !");

    }

}