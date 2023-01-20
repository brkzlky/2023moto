<?php

namespace App\Http\Controllers\panel;

use App\Models\Brand;
use App\Models\Listing;
use App\Models\ModelTrim;
use App\Models\BrandModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.admin:admin');
    }

    public function home(Request $r)
    {
        $query = Brand::query();
        if ($r->name_en) {
            $d['brands'] = $query->OrderBy('id', 'DESC')
                ->where('name_en', 'like', '%' . $r->name_en . '%')
                ->with('category')
                ->paginate(10);
        } elseif (is_null($r->name_en)) {
            $d['brands'] = $query->OrderBy('id', 'DESC')
                ->with('category')
                ->paginate(10);
        }
        $d['page_title'] = 'Brand List';
        return view('panel.pages.brand', $d);
    }
    public function add(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);
        if ($validated->fails()) {
            return redirect()->route('admin.brands.home')->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $n = new Brand();
                $n->brand_guid = Str::uuid();
                $n->name_en = $r->name_en;
                $n->category_guid = $r->category_guid;
                $n->slug = Str::slug($r->name_en);
                $n->status = '1';
                $n->save();
                DB::commit();
                return redirect()->route('admin.brands.detail', $n->brand_guid)->with('success', 'Brand is added succesfully');
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                return redirect()->route('admin.brands.home');
            }
        }
    }
    public function detail($brand_guid)
    {
        $d['brand'] = Brand::where('brand_guid', $brand_guid)->first();
        $d['brand_models'] = BrandModel::where('brand_guid', $brand_guid)->paginate(10);
        $d['listing_count'] = Listing::where('brand_guid', $brand_guid)->count();
        return view('panel.pages.brand_detail', $d);
    }
    public function update(Request $r)
    {

        try {
            $update = Brand::where('brand_guid', $r->brand)->first();
            $update->name_en = $r->name_en;
            $update->name_ar = $r->name_ar;
            if (!is_null($r->logo)) {
                $file_name = $r->file('logo')->getClientOriginalName();
                $r->file('logo')->move(storage_path('app/public/brand_logos/'), $file_name);
                $update->logo = $file_name;
                $update->update();
                return redirect()->back()->with('successBrandUpdate', 'successBrandUpdate');
            } else {
                $update->update();
                return redirect()->back()->with('successBrandUpdate', 'successBrandUpdate');
            }
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }
    public function model_add(Request $r)
    {
        try {
            DB::beginTransaction();
            $n = new BrandModel();
            $n->brand_guid = $r->brand_guid;
            $n->model_guid = Str::uuid();
            $n->name_en = $r->name_en;
            $n->slug = Str::slug($r->name_en);
            $n->status = '1';
            $n->save();
            DB::commit();
            return redirect()->back()->with('successModelAdd', 'successModelAdd');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
    }
    public function model_detail($slug)
    {
        $d['model'] = BrandModel::where('slug', $slug)->first();
        $d['listing_count'] = Listing::where('model_guid', $d['model']->model_guid)->count();
        $d['page_title'] = $d['model']->name_en;
        $d['trims'] = ModelTrim::where('model_guid', $d['model']->model_guid)->orderBy("year")->paginate(10);
        return view('panel.pages.model_detail', $d);
    }
    public function model_detail_update(Request $r)
    {
        try {
            DB::beginTransaction();
            $update = BrandModel::where('model_guid', $r->model_guid)->first();
            $update->name_en = $r->name_en;
            $update->slug = Str::slug($r->name_en);
            $update->name_ar = $r->name_ar;
            $update->status = $r->status;
            $update->update();
            DB::commit();
            $d['model'] = $update;
            return redirect()->route('admin.brands.model.detail', $d['model']->slug)->with('successModelUpdate', 'successModelUpdate');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
    }
    public function trim_add(Request $r)
    {
        try {
            DB::beginTransaction();
            $n = new ModelTrim();
            $n->trim_guid = Str::uuid();
            $n->model_guid = $r->model_guid;
            $n->name_en = $r->name_en;
            $n->slug = Str::slug($r->name_en);
            $n->status = '1';
            $n->save();
            DB::commit();
            return redirect()->back()->with('successTrimAdd', 'successTrimAdd');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
    }
    public function trim_detail($slug)
    {
        $d['trim'] = ModelTrim::where('slug', $slug)->first();
        $d['listing_count'] = Listing::where('trim_guid', $d['trim']->trim_guid)->count();
        $d['page_title'] = $d['trim']->name_en;
        return view('panel.pages.trim_detail', $d);
    }
    public function trim_update(Request $r)
    {
        try {
            DB::beginTransaction();
            $u = ModelTrim::where('trim_guid', $r->trim_guid)->first();
            $u->name_en = $r->name_en;
            $u->name_ar = $r->name_ar;
            $u->status = $r->status;
            $u->year = $r->year;
            $u->slug = Str::slug($r->name_en);
            $u->update();
            $d['trim'] = $u;
            DB::commit();
            return redirect()->route('admin.brands.trim.detail', $d['trim']->slug)->with('successTrimUpdate', 'successTrimUpdate');
        } catch (\Throwable $th) {
            DB::rollback();
            return view('panel.pages.trim_detail', $d);
        }
    }
}
