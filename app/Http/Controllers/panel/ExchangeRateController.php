<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use DB;

class ExchangeRateController extends Controller
{
    public function home(Request $date_req)
    {
        $d['exchange_rate'] = null;
        $d['selected_date'] = null;
        $d['currencies'] = Currency::get();
        $d['rates'] = [];
        
        if ($date_req->has('date')) {
            $rates = ExchangeRate::whereDate('validity_date', $date_req->date )->get();
            if (count($rates) > 0) {
                $d['exchange_rate'] = $rates;
            }
            $d['selected_date'] = $date_req->date;
        } else {
            $d['exchange_rate'] = DB::select(DB::raw('SELECT * FROM (  SELECT  DATE(MAX(validity_date)) AS validity_date FROM exchange_rates) AS latest_rates INNER JOIN exchange_rates ON DATE(exchange_rates.validity_date) = latest_rates.validity_date'));
        }


        if (is_null($d['exchange_rate'])) {
            $d['rates'] = null;
        } else {
            foreach ($d['exchange_rate'] as $r) {
                foreach ($d['currencies'] as $c) {
                    if ($r->from == $c->currency_guid) {
                        $d['rates'][$r->from][$r->to] = $r->price;
                    }
                }
            }
        }

        

        $d['page_title'] = 'Exchange Rates';
        return view('panel.pages.exchange_rate', $d);
    }
}
