<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\BoxQuestion;

class QuestionsNormalizeMasterbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:normalize-masterbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize the questions system for new version. (Drop children details)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Deleting the children details question');

        BoxQuestion::where('type', 'children_details')->delete();

        $this->line('End of process.');
    }
}
