<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Validator;
use Str;
use DB;

class SettingController extends Controller
{
    public function home()
    {
        $d['page_title'] = "Site Settings";
        $d['settings'] = Setting::get();
        return view('panel.pages.setting', $d);
    }
    public function add(Request $r)
    {

        $validated = Validator::make($r->all(), [
            'title_en' => 'required',
            'setting_value' => 'required',
        ]);
        if ($validated->fails()) {
            return redirect()->back()->with('errorSettingValidate', 'errorSettingValidate');
        } else {
            try {
                DB::beginTransaction();
                $n = new Setting();
                $n->setting_guid = Str::uuid();
                $n->title_en = $r->title_en;
                $n->title_ar = $r->title_ar;
                $n->slug = Str::slug($r->title_en);
                $n->setting_value = $r->setting_value;
                $n->setting_type = $r->setting_type;
                $n->input_type = $r->input_type;
                $n->save();
                DB::commit();
                return redirect()->back()->with('settingSuccess', 'settingSuccess');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back()->with('settingError', 'settingError');
            }
        }
    }
    public function delete(Request $r)
    {
        try {
            DB::beginTransaction();
            $d = Setting::where('setting_guid', $r->setting_guid)->delete();
            DB::commit();
            return redirect()->back()->with('settingDeleteSuccess', 'settingDeleteSuccess');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('settingDeleteError', 'settingDeleteError');
        }
    }
    public function detail($setting_guid)
    {

        $d['setting'] = Setting::where('setting_guid', $setting_guid)->first();

        return view('panel.pages.setting_detail', $d);
    }
    public function update(Request $r)
    {
        try {
            DB::beginTransaction();
            $u = Setting::where('setting_guid', $r->setting_guid)->first();
            $u->title_en = $r->title_en;
            $u->slug = Str::slug($r->title_en);
            $u->title_ar = $r->title_ar;
            $u->setting_value = $r->setting_value;
            $u->setting_type = $r->setting_type;
            $u->update();
            DB::commit();
            return redirect()->route('admin.settings.detail',$r->setting_guid)->with('updateSuccess','updateSuccess');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.settings.detail',$r->setting_guid)->with('updateError','updateError');
        }
    }
}
