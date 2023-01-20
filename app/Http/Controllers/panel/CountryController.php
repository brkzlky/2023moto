<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function home(Request $r)
    {
        $d['page_title'] = 'Countries List';

        $query = Country::query();

        if ($r->status || $r->status == '0') {
            $query->where('status', +$r->status);
        }

        if ($r->search) {
            $query->where('name', 'like', "%$r->search%");
        }

        $d['countries'] = $query->withCount('states', 'cities')
            ->paginate(10);
        return view('panel.pages.countries', $d);
    }

    public function detail($country_guid)
    {
        $query = Country::query();
        $d['country'] = $query->where('country_guid', $country_guid)
            ->withCount('states', 'cities')
            ->first();

        $query = Country::query();
        $d['states'] = $query->where('country_guid', $country_guid)
            ->first()
            ->states()
            ->withCount('cities')
            ->paginate(10, ['*'], 'states');

        $query = Country::query();
        $d['cities'] = $query->where('country_guid', $country_guid)
            ->first()
            ->cities()
            ->paginate(10, ['*'], 'cities');

        $d['page_title'] =  $d['country']->name . ' List';

        return view('panel.pages.countries_detail', $d);
    }

    public function update(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'phonecode' => 'required|min:1',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.countries.detail', $r->country_guid)->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $country = Country::where('country_guid', $r->country_guid)->first();
                if ($country) {
                    $country->phonecode = $r->phonecode;
                    $country->status = +$r->status;
                    $country->save();
                };
                DB::commit();
                return redirect()->route('admin.countries.detail', $r->country_guid)->with('successUpdateCountry', 'successUpdateCountry');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function status($country_guid)
    {
        try {
            DB::beginTransaction();
            $country = Country::where('country_guid', $country_guid)->first();
            $country->status = $country->status == 1 ? 0 : 1;
            $country->save();
            DB::commit();
            return redirect()->route('admin.countries.home')->with('successUpdateCountry', 'successUpdateCountry');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
    }
}
