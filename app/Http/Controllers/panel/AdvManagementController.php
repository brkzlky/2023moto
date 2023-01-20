<?php

namespace App\Http\Controllers\panel;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdvManagementController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.admin:admin');
    }

    public function home()
    {
        $d['page_title'] = 'Adv Management';
        $query = Advertisement::query();
        $d['advertisements'] = $query->get();

        return view('panel.pages.adv_management', $d);
    }

    public function detail($adv_guid)
    {
        $d['page_title'] = 'Adv Management Detail';
        $d['adv'] = Advertisement::where("adv_guid", $adv_guid)->first();

        return view('panel.pages.adv_management_detail', $d);
    }

    public function add(Request $r)
    {
        try {
            DB::beginTransaction();

            $adv = new Advertisement();
            $adv->adv_guid = Str::uuid();
            if($r->hasFile('image')) {
                $image = $r->file('image')->getClientOriginalName();
                $r->file('image')->move(storage_path('app/public/adv/'), $image);
                $adv->image = $image;
            }
            $adv->link = $r->link;
            $adv->page_url = $r->page_url;
            $adv->save();

            DB::commit();
            return redirect()->route('admin.advm.detail', $adv->adv_guid)->with('success', 'Advertisement is added succesfully');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->route('admin.advm.home')->with('error', 'Error occurred during operation');
        }
    }

    public function update(Request $r)
    {
        try {
            DB::beginTransaction();

            $adv = Advertisement::where("adv_guid", $r->adv_guid)->first();
            if($r->hasFile('image')) {
                $image = $r->file('image')->getClientOriginalName();
                $r->file('image')->move(storage_path('app/public/adv/'), $image);
                $adv->image = $image;
            }
            $adv->link = $r->link;
            $adv->page_url = $r->page_url;
            $adv->update();

            DB::commit();
            return redirect()->route('admin.advm.detail', $adv->adv_guid)->with('advUpdateSuccess', 'Advertisement is updated succesfully');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->back()->with('advUpdateError', 'Error occurred during operation');
        }
    }

    public function delete(Request $r)
    {
        try {
            // Attribute delete with observer
            DB::beginTransaction();
            $delete = Advertisement::where('adv_guid', $r->adv_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.advm.home')->with('advDeleteSuccess', 'Advertisement is deleted successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.advm.home')->with('advDeleteError', 'Error occurred during operation');;
        }
    }
}
