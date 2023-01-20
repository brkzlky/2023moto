<?php

namespace App\Console\Commands;

use App\Models\Listing;
use Illuminate\Console\Command;

class ListingExpireControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mtvg:listingExpireControl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control Listings Expire Dates';

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
     * @return int
     */
    public function handle()
    {
        $today = date('Y-m-d');
        $min = date('Y-m-d', strtotime("-2 days"));
        $max = date('Y-m-d', strtotime("+4 days"));
        $listings = Listing::where("expire_control","<",$today)->where("expire_at","<",$max)->where("status","1")->limit(100)->get();
        foreach($listings as $li) {
            if(date('Y-m-d', strtotime($li->expire_at)) < $today) {
                $li->status = '0';
                $li->expired = '1';
                $li->expire_control = $today;
                $li->update();
            }
        }
        //return Command::SUCCESS;
    }
}
