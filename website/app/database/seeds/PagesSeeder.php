<?php

class PagesSeeder extends Seeder {

    public function run()
    {

        DB::table('pages')->delete();

        $pages = array(

            'home' => ["Page d'accueil", "Ma super page d'accueil !"],
            'contact' => ["Page contact", "Ma page contact !"],
            'legals' => ["Page légal", "Ma page légal !"],
            'facebook' => ["Lien Facebook", "http://www.facebook.com"],
            'bill' => ['Facture légal', "Du blabla concernant la loi"],
            'company_address' => ["Adresse de Bordeaux in Box", "Bordeaux in Box - 2 rue de je sais pas quoi, Bordeaux (33470)"],

            );

        $this->pages($pages);

    }

    private function pages($pages)
    {

        foreach ($pages as $slug => $datas)
        {

            $this->command->info("Generating $slug page ...");

            Page::create(array(

                'slug' => $slug,
                'title' => $datas[0],
                'content' => $datas[1]

                ));

        }

    } 

}