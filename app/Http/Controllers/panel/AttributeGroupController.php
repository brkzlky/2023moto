<?php

namespace App\Http\Controllers\panel;

use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AttributeGroup;
use App\Models\AttributeMapping;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AttributeGroupController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.admin:admin');
    }

    public function home(Request $r)
    {
        // Attribute groups list
        $d['page_title'] = 'Attribute Groups List';
        $query = AttributeGroup::query();

        $query->OrderBy('id', 'DESC')
            ->withCount('attribute_info', 'category_info');
        // Attribute groups Search
        if ($r->name_en) {
            $query
                ->where('name_en', 'like', '%' . $r->name_en . '%')
                ->orWhereHas('category_info', function ($q) use ($r) {
                    $q->where('name_en', 'like', '%' . $r->name_en . '%');
                });
        }
        $d['attribute_groups'] = $query
            ->paginate(10);

        $d['categories'] = Category::get();

        return view('panel.pages.attribute_groups', $d);
    }

    public function add(Request $r)
    {
        // Attribute group add
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.attribute_groups.home')->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $attribute_group = new AttributeGroup();
                $attribute_group->ag_guid = Str::uuid();
                $attribute_group->category_guid = $r->category_guid;
                $attribute_group->name_en = $r->name_en;
                $attribute_group->slug = Str::slug($r->name_en);
                $attribute_group->save();
                DB::commit();
                return redirect()->route('admin.attribute_groups.detail', $attribute_group->ag_guid)->with('successAddAttributeGroup', 'successAddAttributeGroup');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.attribute_groups.home');
            }
        }
    }

    public function detail($ag_guid, Request $r)
    {
        // Attribute group with attributes
        $query  = AttributeGroup::query();
        $attribute_group  = $query->where('ag_guid', $ag_guid)->OrderBy('id', 'DESC')
            ->withCount('attribute_info')
            ->first();

        // Attributes guid attached to the attribute group
        $array = [];
        $array =  $attribute_group->attribute_info->map(function ($attr) {
            return $attr->attribute_guid;
        });
        $d['attribute_group'] = $attribute_group;

        $d['categories'] = Category::get();

        // Attribute Search
        $query  = AttributeGroup::query();
        if ($r->name_en_attribute) {
            $d['attribute_info'] = $query->where('ag_guid', $ag_guid)
                ->OrderBy('id', 'DESC')
                ->first()
                ->attribute_info()
                ->where('name_en', 'like', '%' . $r->name_en_attribute . '%')
                ->paginate(10, ["*"], "attribute");
            $d['search_attribute'] = 1;
        }
        if ($r->name_en_attribute == null) {
            $d['attribute_info']  = $query->where('ag_guid', $ag_guid)
                ->OrderBy('id', 'DESC')
                ->first()
                ->attribute_info()
                ->paginate(10, ["*"], "attribute");
            $d['search_attribute'] = 0;
        }

        // Attributes guid not attached to the attribute group
        $d['attributes'] = Attribute::whereNotIn('attribute_guid', $array)->get();

        $d['page_title'] =  $d['attribute_group']->name_en . ' Detail';

        // Count
        return view('panel.pages.attribute_groups_detail', $d);
    }


    public function update(Request $r)
    {
        // Attribute group update
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.attributes.detail', $r->attribute_guid)->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $attribute_group = AttributeGroup::where('ag_guid', $r->ag_guid)->first();
                $attribute_group->name_en = $r->name_en;
                $attribute_group->name_ar = $r->name_ar;
                $attribute_group->input_type = $r->input_type;
                $attribute_group->status = +$r->status;
                $attribute_group->multiple_select = +$r->multiple_select;
                $attribute_group->filterable = +$r->filterable;
                $attribute_group->save();
                DB::commit();
                return redirect()->route('admin.attribute_groups.detail', $r->ag_guid)->with('successUpdateAttributeGroup', 'successUpdateAttributeGroup');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function delete(Request $r)
    {
        try {
            // Attribute group delete with observer
            DB::beginTransaction();
            $delete = AttributeGroup::where('ag_guid', $r->ag_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.attribute_groups.home')->with('successDeleteAttributeGroup', 'successDeleteAttributeGroup');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.attribute_groups.home');
        }
    }

    public function attribute_add(Request $r)
    {
        // Adding an attributes to the attribute group
        if (isset($r->attr_guid)) {
            foreach ($r->attr_guid as $attribute) {
                try {
                    DB::beginTransaction();
                    $attribute_map = new AttributeMapping();
                    $attribute_map->attribute_guid = $attribute;
                    $attribute_map->ag_guid = $r->ag_guid;
                    $attribute_map->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->back();
                }
            }
            return redirect()->route('admin.attribute_groups.detail', $r->ag_guid)->with('successAddedAttribute', 'successAddedAttribute');
        }
        return redirect()->back();
    }

    public function attribute_delete(Request $r)
    {
        // Deleted an attributes to the attribute group
        if (isset($r->attr_guid)) {
            foreach ($r->attr_guid as $attribute) {
                try {
                    DB::beginTransaction();
                    AttributeMapping::where('attribute_guid', $attribute)->where('ag_guid', $r->ag_guid)->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->back();
                }
            }
            return redirect()->route('admin.attribute_groups.detail', $r->ag_guid)->with('successDeletedAttribute', 'successDeletedAttribute');
        }
        return redirect()->back();
    }
}
