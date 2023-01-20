<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\ExchangeRate;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate as ClassesExchangeRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mtvg:syncCurrency';

    //Veritabanında ki para birimi kur güncellemesi
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $other=[];
        $exchangeRates = new ClassesExchangeRate();

        $currencies = Currency::get();
        foreach ($currencies as $c) {
            $base_currency=$c->currency_guid;
            foreach ($currencies as $currency) {
                if ($currency->label != $c->label) {
                    $other[] = $currency->label;
                    $guid[$currency->label]=$currency->currency_guid;
                }else{
                    $guid[$currency->label]=$currency->currency_guid;
                }
            }

            $result = $exchangeRates->exchangeRate($c->label, $other);
           
          
            foreach ($result as $curr => $r) {
                $data=new ExchangeRate();
                $data->from=$base_currency;
                $data->to=$guid[$curr];
                $data->price=$r;
                $data->validity_date=Carbon::now();
                $data->save();
            }
            $other=[];
            
        }
    }
}
