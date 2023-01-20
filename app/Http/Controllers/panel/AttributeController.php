<?php

namespace App\Http\Controllers\panel;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\AttributeImport;
use App\Models\AttributeMapping;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;


class AttributeController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.admin:admin');
    }

    public function home(Request $r)
    {
        // Attribute list
        $d['page_title'] = 'Attribute List';
        $query = Attribute::query();

        // Attribute Search
        if ($r->name_en) {
            $d['attributes'] = $query->OrderBy('id', 'DESC')
                ->where('name_en', 'like', '%' . $r->name_en . '%')
                ->withCount('attributes_info')
                ->with('attributes_info')
                ->paginate(10);
        }
        if ($r->name_en == null) {
            $d['attributes'] = $query->OrderBy('id', 'DESC')
                ->withCount('attributes_info')
                ->with('attributes_info')
                ->paginate(10);
        }

        return view('panel.pages.attributes', $d);
    }

    public function add(Request $r)
    {
        // Attribute Add
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.attributes.home')->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $attribute = new Attribute();
                $attribute->name_en = $r->name_en;
                $attribute->slug = Str::slug($r->name_en);
                $attribute->attribute_guid = Str::uuid();
                $attribute->save();
                DB::commit();
                return redirect()->route('admin.attributes.home')->with('successAddAttribute', 'successAddAttribute');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.attributes.home');
            }
        }
    }
    public function multiple_add(Request $r)
    {
        $data = Excel::toArray(new AttributeImport, $r->file('file'));

        foreach ($data as $d) {
            if ($d[0][0] == 'name_en' && $d[0][1] == 'name_ar') {
                foreach ($d as $row) {
                    if ($row[0] == 'name_en' && $row[1] == 'name_ar') {
                        continue;
                    } else {
                        $cntrl_en = Attribute::where('name_en', $row[0])->first();
                        $cntrl_ar = Attribute::where('name_ar', $row[1])->first();
                        if (is_null($cntrl_en) && is_null($cntrl_ar)) {
                            $n = new Attribute();
                            $n->attribute_guid = Str::uuid();
                            $n->name_en = $row[0];
                            $n->name_ar = $row[1];
                            $n->slug = Str::slug($n->name_en);
                            $n->save();
                        }else {
                            return redirect()->route('admin.attributes.home')->with('errorAddMultipleAttribute','errorAddMultipleAttribute');
                        }
                    }
                }
            } else {
                return redirect()->route('admin.attributes.home')->with('errorTableMultipleAdd','errorTableMultipleAdd');
            }
        }

        return redirect()->route('admin.attributes.home')->with('succcesMultipleAdd','succcesMultipleAdd');

    }

    public function detail($attribute_guid, Request $r)
    {
        // Attribute detail
        $query = Attribute::query();
        $d['attribute'] = $query->where('attribute_guid', $attribute_guid)
            ->withCount('attributes_info')
            ->with('attributes_info')
            ->first();

        if ($r->name_en) {
            $d['attribute_groups'] = $query->where('attribute_guid', $attribute_guid)->first()
                ->attributes_info()
                ->where('name_en', 'like', '%' . $r->name_en . '%')
                ->paginate(10, ["*"], "attribute_group");
            $d['search_on'] = 1;
        }
        if ($r->name_en == null) {
            $d['attribute_groups'] = $query->where('attribute_guid', $attribute_guid)->first()
                ->attributes_info()
                ->paginate(10, ["*"], "attribute_group");
            $d['search_on'] = 0;
        }

        $d['page_title'] = $d['attribute']->name_en . ' Detail';

        return view('panel.pages.attributes_detail', $d);
    }

    public function update(Request $r)
    {
        // Attribute update
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.attributes.detail', $r->attribute_guid)->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $attribute = Attribute::where('attribute_guid', $r->attribute_guid)->first();
                $attribute->name_en = $r->name_en;
                $attribute->name_ar = $r->name_ar;
                $attribute->save();
                DB::commit();
                return redirect()->route('admin.attributes.detail', $r->attribute_guid)->with('successUpdateAttribute', 'successUpdateAttribute');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function delete(Request $r)
    {
        try {
            // Attribute delete with observer
            DB::beginTransaction();
            $delete = Attribute::where('attribute_guid', $r->attribute_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.attributes.home')->with('successDeleteAttribute', 'successUpdateAttribute');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.attributes.home');
        }
    }
}
