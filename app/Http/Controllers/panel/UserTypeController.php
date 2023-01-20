<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Validator;
use Str;
use DB;

class UserTypeController extends Controller
{
    public function home()
    {
        // User Types list
        $d['page_title'] = 'User Types List';
        $d['user_types'] = UserType::OrderBy('id', 'DESC')->paginate(10);
        return view('panel.pages.user_types', $d);
    }

    public function add(Request $r)
    {
        // User Types Add
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.user_types.home')->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $user_type = new UserType();
                $user_type->name_en = $r->name_en;
                $user_type->type_guid = Str::uuid();
                $user_type->save();
                DB::commit();
                return redirect()->route('admin.user_types.home')->with('successAddUserTypes', 'successAddUserTypes');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.user_types.home');
            }
        }
    }

    public function detail($type_guid)
    {
        // User Types detail
        $d['page_title'] = 'User Types Detail';
        $d['user_type'] = UserType::where('type_guid', $type_guid)->first();
        return view('panel.pages.user_types_detail', $d);
    }

    public function update(Request $r)
    {
        // User Types update
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.user_types.detail', $r->type_guid)->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $user_type = UserType::where('type_guid', $r->type_guid)->first();
                $user_type->name_en = $r->name_en;
                $user_type->name_ar = $r->name_ar;
                $user_type->free_listing = $r->free_listing;
                $user_type->free_listing_period = $r->free_listing_period;
                $user_type->status = +$r->status;
                $user_type->save();
                DB::commit();
                return redirect()->route('admin.user_types.detail', $r->type_guid)->with('successUpdateUserTypes', 'successUpdateUserTypes');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function delete(Request $r)
    {
        try {
            DB::beginTransaction();
            UserType::where('type_guid', $r->type_guid)->delete();
            $users = User::where('type_guid', $r->type_guid)->get();
            foreach ($users as $user) {
                $user = User::where('type_guid', $user->type_guid)->first();
                $user->type_guid = 1;
                $user->save();
            }

            DB::commit();
            return redirect()->route('admin.user_types.home')->with('successDeleteUserTypes', 'successDeleteUserTypes');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.user_types.home');
        }
    }
}
