<?php

namespace App\Http\Controllers\site;

use App;
use Str;
use Session;
use App\Models\Faq;
use App\Models\Brand;
use App\Models\Listing;
use App\Models\BankRate;
use App\Models\Location;
use App\Models\Expertise;
use App\Models\ModelTrim;
use App\Models\BrandModel;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpertiseController extends Controller
{
    //Expertise Page
    public function expertise()
    {
        $d['faqs'] = Faq::where("type","expertise")->get();
        return view('site.page.expertise', $d);
    }

    //Get Brands
    public function expertise_brands(Request $r)
    {
        $key = $r->key;
        $lang = Session::get('current_language');
        if($lang == 'ar') {
            $allbrands = Brand::select("brand_guid","name_ar as name")->where("status","1")->where("name_ar","like","%".$key."%")->get();
        } else {
            $allbrands = Brand::select("brand_guid","name_en as name")->where("status","1")->where("name_en","like","%".$key."%")->get();
        }

        if(count($allbrands) == 0) {
            $allbrands = [];
        }

        return response()->json($allbrands, 200);
    }

    //Get Brand Models
    public function expertise_models(Request $r)
    {
        $key = $r->key;
        $brand = $r->brand;
        $lang = Session::get('current_language');
        if($lang == 'ar') {
            $allmodels = BrandModel::select("model_guid","name_ar as name")->where("brand_guid",$brand)->where("status","1")->where("name_ar","like","%".$key."%")->get();
        } else {
            $allmodels = BrandModel::select("model_guid","name_en as name")->where("brand_guid",$brand)->where("status","1")->where("name_en","like","%".$key."%")->get();
        }

        if(count($allmodels) == 0) {
            $allmodels = [];
        }

        return response()->json($allmodels, 200);
    }

    //Get Brand Model Trims
    public function expertise_trims(Request $r)
    {
        $key = $r->key;
        $brand = $r->brand;
        $model = $r->model;
        $year = $r->year;
        $alltrims = [];
        $lang = Session::get('current_language');
        if($lang == 'ar') {
            $trims = ModelTrim::select("trim_guid","name_ar as name","year")->where("model_guid",$model)->where("year",$year)->where("status","1")->where("name_ar","like","%".$key."%")->get();
        } else {
            $trims = ModelTrim::select("trim_guid","name_en as name","year")->where("model_guid",$model)->where("year",$year)->where("status","1")->where("name_en","like","%".$key."%")->get();
        }

        if(count($trims) > 0) {
            foreach($trims as $t) {
                $search = array_search($t->name, array_column($alltrims, 'name'));
                if($search === false) {
                    $alltrims[] = array(
                        "name" => $t->name,
                        "trim_guid" => $t->trim_guid
                    );
                }
            }

            array_multisort( array_column($alltrims, "name"), SORT_ASC, $alltrims );

        }

        return response()->json($alltrims, 200);
    }

    //Apply expertise form
    public function expertise_form(Request $r)
    {
        $exp = new Expertise();
        $exp->expertise_guid = Str::uuid();
        $exp->fullname = $r->fullname;
        $exp->email = $r->email;
        $exp->phone = $r->phone;
        $exp->brand_guid = $r->brand_guid;
        $exp->model_guid = $r->model_guid;
        $exp->trim_guid = $r->trim_guid;
        $exp->model_year = $r->model_year;
        $exp->mileage = $r->mileage;
        $exp->save();

        $d['expertise_request'] = true;
        $d['success_title'] = __('alert.success_title');
        $d['success_msg'] = __('alert.exp_request_success');
        return redirect()->back()->with($d);
    }

}