<?php

namespace App\Http\Controllers\panel;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AttributeGroup;
use App\Models\ListingAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CategoryAttributeGroup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.admin:admin');
    }

    public function home(Request $r)
    {
        // Category Search
        $query = Category::query();

        $query->where('related_to', null)
            ->withCount('location_info', 'children', 'listings', 'attr_groups_info')
            ->orderBy('id', 'DESC');

        if ($r->name_en) {
            $query->where('name_en', 'like', '%' . $r->name_en . '%');
        }

        $d['categories'] = $query->paginate(10);

        $d['page_title'] = 'Categories List';
        return view('panel.pages.categories', $d);
    }

    public function add(Request $r)
    {

        // Category add
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.categories.home')->with('errorValidate', 'errorValidate');
        } else {
            if ($r->related_to == null) {
                try {
                    // Parent category
                    DB::beginTransaction();
                    $category = new Category();
                    $category->name_en = $r->name_en;
                    $category->slug = Str::slug($r->name_en);
                    $category->category_guid = Str::uuid();
                    $category->save();
                    DB::commit();
                    return redirect()->route('admin.categories.home')->with('successAddParentCategory', 'successAddParentCategory');
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->route('admin.categories.home');
                }
            } else {
                try {
                    // Child category
                    DB::beginTransaction();
                    $category = new Category();
                    $category->name_en = $r->name_en;
                    $category->slug = Str::slug($r->name_en);
                    $category->category_guid = Str::uuid();
                    $category->related_to = $r->related_to;
                    $category->save();
                    DB::commit();
                    return redirect()->route('admin.categories.home')->with('successAddChildCategory', 'successAddChildCategory');
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->route('admin.categories.home');
                }
            }
        }
    }

    public function detail($category_guid, Request $r)
    {
        // Categories
        $query = Category::query();
        $d['categories'] = $query->where('related_to', null)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        // Category detail
        $d['category'] = Category::where('category_guid', $category_guid)
            ->withCount('location_info', 'children', 'listings', 'attr_groups_info')
            ->orderBy('id', 'DESC')
            ->first();

        // Category location search
        if ($r->name_en_location) {
            // location-related category
            $category_location = Category::where('category_guid', $category_guid)->first()
                ->location_info()
                ->where('name_en', 'like', '%' . $r->name_en_location . '%')
                ->orderBy('id', 'DESC')
                ->paginate(10, ["*"], "locations");
            $d['search_locations'] = 1;
        }

        if ($r->name_en_location == null) {
            // location-related category
            $category_location = Category::where('category_guid', $category_guid)->first()
                ->location_info()
                ->orderBy('id', 'DESC')
                ->paginate(10, ["*"], "locations");
            $d['search_locations'] = 0;
        }

        $d['category_locations'] = $category_location;

        // Category location search
        if ($r->name_en_attribute_group) {
            // location-related category
            $category_attr_groups = Category::where('category_guid', $category_guid)->first()
                ->attr_groups_info()
                ->where('name_en', 'like', '%' . $r->attr_groups . '%')
                ->orderBy('id', 'DESC')
                ->paginate(10, ["*"], "attr_groups");
            $d['search_attr_groups'] = 1;
        }

        if ($r->name_en_attribute_group == null) {
            // location-related category
            $category_attr_groups = Category::where('category_guid', $category_guid)->first()
                ->attr_groups_info()
                ->orderBy('id', 'DESC')
                ->paginate(10, ["*"], "attr_groups");
            $d['search_attr_groups'] = 0;
        }

        $d['category_attribute_groups'] = $category_attr_groups;

        // Category children search
        if ($r->name_en_children) {
            // Children by category
            $d['category_children'] = Category::where('related_to', $category_guid)
                ->where('name_en', 'like', '%' . $r->name_en_children . '%')
                ->orderBy('id', 'DESC')
                ->paginate(10, ["*"], "children");
            $d['search_children'] = 1;
        }

        if ($r->name_en_children == null) {
            // Children by category
            $d['category_children'] = Category::where('related_to', $category_guid)
                ->orderBy('id', 'DESC')
                ->paginate(10, ["*"], "children");
            $d['search_children'] = 0;
        }

        // Count
        $d['count_parent'] = $query->count();

        $d['page_title'] = $d['category']->name_en . ' Detail';
        return view('panel.pages.categories_detail', $d);
    }

    public function update(Request $r)
    {
        // Category add
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.categories.detail', $r->category_guid)->with('errorValidate', 'errorValidate');
        } else {
            if ($r->related_to == null) {
                try {
                    // Parent category
                    DB::beginTransaction();
                    $category = Category::where('category_guid', $r->category_guid)->first();
                    $category->status = +$r->status;
                    $category->name_en = $r->name_en;
                    $category->slug = Str::slug($r->name_en);
                    $category->name_ar = $r->name_ar;
                    $category->queue = +$r->queue;
                    $category->related_to = null;
                    if ($r->hasFile('image')) {
                        if (file_exists('storage/images/categories/' . $category->image)) {
                            if ($category->image != null) {
                                Storage::delete('public/images/categories/' . $category->image);
                            }
                        }
                        $file_name = $r->category_guid .  Str::slug(pathinfo($r->file('image')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $r->image->getClientOriginalExtension();
                        $r->image->storeAs('public/images/categories', $file_name);
                        $category->image = $file_name;
                    }
                    $category->save();
                    DB::commit();
                    return redirect()->route('admin.categories.detail', $r->category_guid)->with('successUpdateParentCategory', 'successUpdateParentCategory');
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->route('admin.categories.home');
                }
            } else {
                try {
                    // Child category
                    DB::beginTransaction();
                    $category = Category::where('category_guid', $r->category_guid)->first();
                    $category->status = +$r->status;
                    $category->name_en = $r->name_en;
                    $category->slug = Str::slug($r->name_en);
                    $category->name_ar = $r->name_ar;
                    $category->queue = +$r->queue;
                    $category->related_to = $r->related_to;
                    if ($r->hasFile('image')) {
                        if (file_exists('storage/images/categories/' . $category->image)) {
                            if ($category->image != null) {
                                Storage::delete('public/images/categories/' . $category->image);
                            }
                        }
                        $file_name = $r->category_guid .  Str::slug(pathinfo($r->file('image')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $r->image->getClientOriginalExtension();
                        $r->image->storeAs('public/images/categories', $file_name);
                        $category->image = $file_name;
                    }
                    $category->save();
                    DB::commit();
                    return redirect()->route('admin.categories.detail', $r->category_guid)->with('successUpdateChildCategory', 'successUpdateChildCategory');
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->route('admin.categories.home');
                }
            }
        }
    }

    public function delete(Request $r)
    {
        if ($r->delete_children) {
            // Deleted the category with children
            try {
                // Category children delete with observer
                DB::beginTransaction();
                $category = Category::where('category_guid', $r->category_guid)->first();
                if (file_exists('storage/images/categories/' . $category->image)) {
                    if ($category->image != null) {
                        Storage::delete('public/images/categories/' . $category->image);
                    }
                }
                $category->delete();
                $children = Category::where('related_to', $r->category_guid)->get();
                if ($children) {
                    foreach ($children as $child) {
                        if (file_exists('storage/images/categories/' . $child->image)) {
                            if ($child->image != null) {
                                Storage::delete('public/images/categories/' . $child->image);
                            }
                        }
                        $child->delete();
                    }
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
            return redirect()->route('admin.categories.home')->with('successDeletedCategoryChildren', 'successDeletedCategoryChildren');
        } else {
            try {
                // Category delete with observer
                $children = Category::where('related_to', $r->category_guid)->get();
                if ($children) {
                    foreach ($children as $child) {
                        try {
                            // Category children transform parent category
                            DB::beginTransaction();
                            $child = Category::where('category_guid', $child->category_guid)->first();
                            $child->related_to = null;
                            $child->save();
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollback();
                            return redirect()->back();
                        }
                    }
                }
                DB::beginTransaction();
                $category = Category::where('category_guid', $r->category_guid)->first();
                if (file_exists('storage/images/categories/' . $category->image)) {
                    if ($category->image != null) {
                        Storage::delete('public/images/categories/' . $category->image);
                    }
                }
                $category->delete();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
            return redirect()->route('admin.categories.home')->with('successDeletedCategory', 'successDeletedCategory');
        }
        return redirect()->back();
    }

    public function delete_inside(Request $r)
    {
        try {
            // Delete child category category detail
            DB::beginTransaction();
            $category = Category::where('category_guid', $r->child_guid)->first();
            if (file_exists('storage/images/categories/' . $category->image)) {
                if ($category->image != null) {
                    Storage::delete('public/images/categories/' . $category->image);
                }
            }
            $category->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
        return redirect()->route('admin.categories.detail', $r->category_guid)->with('successDeletedCategoryChildrenInside', 'successDeletedCategoryChildrenInside');
    }
}
