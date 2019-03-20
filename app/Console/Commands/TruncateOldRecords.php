<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Appointment;

use Carbon\Carbon;

class TruncateOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:OldRecords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Commad will Delete Old Records From Table Appointment';

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
      Appointment::where('date','<',Carbon::today())->each(function($item){
        $item->delete();
      });
    }
}
