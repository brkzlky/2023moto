<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Str;
use DB;

class ColorController extends Controller
{
    public function home()
    {
        $d['page_title'] = 'Color List';
        $d['colors'] = Color::get();
        return view('panel.pages.color', $d);
    }
    public function add(Request $r)
    {
        try {
            DB::beginTransaction();
            $n = new Color();
            $n->color_guid = Str::uuid();
            $n->name_en = $r->name_en;
            $n->name_ar = $r->name_ar;
            $n->status = $r->status;
            $n->save();
            DB::commit();

            return redirect()->route('admin.colors.home')->with('successColorAdd', 'successColorAdd');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.colors.home')->with('errorColorAdd', 'errorColorAdd');
        }
    }
    public function delete(Request $r)
    {
        try {
            DB::beginTransaction();
            $d = Color::where('color_guid', $r->color_guid)->first();
            $d->delete();
            DB::commit();
            return redirect()->route('admin.colors.home')->with('successDeleteColor', 'successDeleteColor');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.colors.home')->with('errorDeleteColor', 'errorDeleteColor');
        }
    }
    public function detail($color_guid)
    {
        $d['color'] = Color::where('color_guid', $color_guid)->first();
        return view('panel.pages.color_detail',$d);
    }
    public function update(Request $r)
    {
        try {
            DB::beginTransaction();
            $u=Color::where('color_guid',$r->color_guid)->first();
            $u->name_en=$r->name_en;
            $u->name_ar=$r->name_ar;
            $u->status=$r->status;
            $u->update();
            DB::commit();
            return redirect()->route('admin.colors.detail',$r->color_guid)->with('colorDetailUpdateSuccess','colorDetailUpdateSuccess');
        } catch (\Throwable $th) {
          DB::rollback();
          return redirect()->route('admin.colors.detail',$r->color_guid)->with('colorDetailUpdateError','colorDetailUpdateError');

        }
    }
}
