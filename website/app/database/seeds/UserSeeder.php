<?php

class UserSeeder extends Seeder {

    public function run()
    {

    	// Number you want to generate
    	$users = 10;

    	Eloquent::unguard();
        DB::table('users')->delete(); // CASCADE DELETE for the linked tables

        $this->users($users);
        $this->transform_to_default_user();
        $this->transform_to_default_admin();

    }

    private function users($users)
    {

        $this->command->info("Generating $users users ...");

        $numUser = 0;

        while ($numUser < $users)
        {

            // User
            $user = new User;

            $user->email = 'user' . $numUser . '@bordeauxinbox.fr';
            $user->password = Hash::make('password' . rand(0,1000));
            $user->role = 'user';

            $user->last_name = array_random(['Ges', 'Schaffner', 'Dupont', 'Richard', 'Boulon', 'Haricot', 'Banane']);
            $user->first_name = array_random(['Jérémie', 'Laurent', 'Jean-Pierre', 'Jean-Claude', 'Charles', 'Rodriguez']);
            $user->city = array_random(['Paris', 'Bordeaux', 'Lyon', 'Marseille', 'Arcachon']);

            $user->address = array_random(['18 rue mes fesses', '20 rues nimportequoi', '33 rue de Bordeaux']);
            $user->zip = array_random(['33000', '33800', '33470','33500']);

            $user->save();

            // Profile (empty)
            $profile = new UserProfile;
            $profile->user()->associate($user);
            $profile->save();

            // We can already build the contract id
            $profile->contract_id = strtoupper(str_random(1)) . rand(100,999) . $user->id . $profile->id;
            $profile->save();

            $numUser++;

        }

        $this->command->info("Users creation done !");

    }

    private function transform_to_default_user()
    {

        $this->command->info("Selecting default user ...");

        $user = User::orderBy(DB::raw('RAND()'))->first();

        $user->email = 'user@user.com';
        $user->password = Hash::make('user');
        $user->role = 'worker'; // Only to test workers

        $user->save();

        $this->command->info("Default user credentials : user@user.com / user");

    }

    private function transform_to_default_admin()
    {

        $this->command->info("Selecting default admin ...");

        $admin = User::orderBy(DB::raw('RAND()'))->first();

        $admin->email = 'admin@admin.com';
        $admin->password = Hash::make('admin');
        $admin->role = 'admin'; // Only to test workers

        $admin->save();

        $this->command->info("Default admin credentials : admin@admin.com / admin");

    }

}
