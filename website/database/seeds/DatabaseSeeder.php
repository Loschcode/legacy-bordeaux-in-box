<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UserSeeder');
        $this->call('ContactSeeder');
        $this->call('PagesSeeder');

        $this->call('DeliverySeriesSeeder');
        $this->call('DeliveryPricesSeeder');
        $this->call('DeliverySettingsSeeder');
        $this->call('DeliverySpotsSeeder');

        $this->call('OrdersSeeder');

    }

}
