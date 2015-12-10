<?php

class ContactSeeder extends Seeder {

    public function run()
    {

    	// Number you want to generate
    	$users = 10;

    	Eloquent::unguard();
        DB::table('contacts')->delete();
        DB::table('contact_settings')->delete();

        $this->command->info("Generating contact settings ...");

        $contact_setting = new ContactSetting;
        $contact_setting->tech_support = 'nounours@bordeauxinbox.fr';
        $contact_setting->com_support = 'lolipop@bordeauxinbox.fr';

        $contact_setting->save();

        $this->command->info("Contact settings creation done !");

    }

}