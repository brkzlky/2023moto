<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\BlockedUser;
use App\Models\Category;
use App\Models\Country;
use App\Models\Listing;
use App\Models\Location;
use App\Models\Message;
use App\Models\User;
use App\Models\UserChat;
use App\Models\UserType;
use Illuminate\Http\Request;
use Validator;
use DB;
use Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function home(Request $r)
    {

        // Attribute list
        $query = User::query();

        // Categories list search
        if ($r->search) {
            $query->where(function ($q) use ($r) {
                $q->where('name', 'like', "%$r->search%")
                    ->orWhere('phone', 'like', "%$r->search%")
                    ->orWhere('email', 'like', "%$r->search%");
            });
        }

        if ($r->status || $r->status == '0') {
            $query->where('status', +$r->status);
        }

        $d['users'] = $query->where('type_guid', $r->type_guid)
            ->OrderBy('id', 'DESC')
            ->withCount('type', 'listings', 'favorite')
            ->paginate(10);

        $d['users']->map(function ($user) {
            $user['user_chat_count'] = $d['users_chat'] = UserChat::where(function ($q) use ($user) {
                $q->orWhere('user_own_guid', $user->user_guid);
                $q->orWhere('user_opposite_guid', $user->user_guid);
            })->count();
            return $user['user_chat_count'];
        });

        if ($d['users'] != '') {
            // Users Type Name
            $type_name = [];
            foreach ($d['users'] as $user) {
                array_push($type_name, $user->type->name_en);
            }
            $d['type_name'] = $type_name;

            // Users Type Guid
            $type_guid = [];
            foreach ($d['users'] as $user) {
                array_push($type_guid, $user->type->type_guid);
            }
            $d['type_guid'] = $type_guid;
        }

        // If users is empty
        if ($d['users'] == '') {
            $user_types =  UserType::where('type_guid', $r->type_guid)->first();
            $d['type_name'] = [$user_types->name_en];
            $d['type_guid'] = [$user_types->type_guid];
        }

        $d['page_title'] = $d['type_name'][0] . ' Users List';

        return view('panel.pages.users', $d);
    }

    public function detail($user_guid, Request $r)
    {
        $d['user'] = User::where('user_guid', $user_guid)
            ->with('type', 'listings', 'favorite')
            ->withCount('listings', 'favorite')
            ->first();

        // User Block list
        $query = BlockedUser::query();
        $d['users_block'] = $query->where(function ($q) use ($user_guid) {
            $q->where('user_guid', $user_guid)
                ->orWhere('blocked_user_guid', $user_guid);
        })->with('owner', 'blocked')->paginate(10, ['*'], 'users_block');

        $d['user_types'] = UserType::get();

        $query = UserChat::query();
        $d['users_chat'] = $query->orWhereHas('receiver', function ($q) use ($user_guid, $r) {
            $q->where('name', 'like', '%' . $r->name_en_user_chat . '%')->where('user_own_guid', $user_guid);
        })->orWhereHas('sender', function ($q) use ($user_guid, $r) {
            $q->where('name', 'like', '%' . $r->name_en_user_chat . '%')->where('user_opposite_guid', $user_guid);
        })->orWhereHas('listing_info', function ($q) use ($user_guid, $r) {
            $q->where('name_en', 'like', '%' . $r->name_en_user_chat . '%')->where('user_own_guid', $user_guid);
        })->orWhereHas('listing_info', function ($q) use ($user_guid, $r) {
            $q->where('name_en', 'like', '%' . $r->name_en_user_chat . '%')->where('user_opposite_guid', $user_guid);
        })->with('listing_info', 'messages_info', 'last_message')->paginate(10, ['*'], 'users_chat');

        $d['users_chat']->map(function ($chat) use ($user_guid) {
            if ($chat->user_opposite_guid == $user_guid) {
                $chat['user_info'] = User::where('user_guid', $chat->user_own_guid)->first();
            } else {
                $chat['user_info'] = User::where('user_guid', $chat->user_opposite_guid)->first();
            }
            return $chat['user_info'];
        });

        if ($r->name_en_user_chat) {
            $d['user_chat_search'] = 1;
        }

        if ($r->name_en_user_chat == null) {
            $d['user_chat_search'] = 0;
        }

        // Users list
        $query = Listing::query();

        $d['user_listings_search'] = 0;
        if (
            $r->search != null ||
            $r->category_guid != null ||
            $r->location_guid != null ||
            $r->status != null ||
            $r->status == '0'
        ) {
            $d['user_listings_search'] = 1;
        }
        // Users list search
        if ($r->search) {
            $query->where(function ($q) use ($r) {
                $q->where('name_en', 'like', "%$r->search%")
                    ->orWhere('listing_no', 'like', "%$r->search%");
            });
        }
        if ($r->category_guid) {
            $query->where('category_guid', $r->category_guid);
        }
        if ($r->location_guid) {
            $query->where('location_guid', $r->location_guid);
        }
        if ($r->status || $r->status == '0') {
            $query->where('status', +$r->status);
        }

        $d['listings'] = $query->where('user_guid', $user_guid)
            ->with('category', 'location', 'user')
            ->orderBy('id', 'DESC')
            ->paginate(10, ['*'], 'listings');

        // Categories
        $query = Category::query();
        $d['categories'] = $query
            ->orderBy('id', 'DESC')
            ->get();

        // Country
        $query = Country::query();
        $d['countries'] = $query
            ->orderBy('name', 'ASC')
            ->get();

        $d['page_title'] = $d['user']->name . ' User Detail';
        return view('panel.pages.users_detail', $d);
    }

    public function chats(Request $r)
    {
        $chat = UserChat::where('user_chat_guid', $r->user_chat_guid)->where(function ($q) use ($r) {
            $q->orWhere('user_own_guid', $r->user_guid);
            $q->orWhere('user_opposite_guid', $r->user_guid);
        })->with('messages_info')->first();

        if ($chat->user_opposite_guid == $r->user_guid) {
            $chat['user_info'] = User::where('user_guid', $chat->user_opposite_guid)->first();
        } else {
            $chat['user_info'] = User::where('user_guid', $chat->user_own_guid)->first();
        }

        return response()->json(['chat' => $chat], 200);
    }

    public function update(Request $r)
    {
        // User update
        $validated = Validator::make($r->all(), [
            'name' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.users.detail', $r->user_guid)->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $user = User::where('user_guid', $r->user_guid)->first();
                $user->name = $r->name;
                $user->phone = $r->phone;
                $user->whatsapp = $r->whatsapp;
                $user->email = $r->email;
                $user->status = +$r->status;
                $user->country_guid = $r->country_guid;
                $user->gender = $r->gender;
                $user->birthday = $r->birthday;
                $user->description = $r->description;
                if ($user->type_guid != $r->type_guid) {
                    $user->type_guid = $r->type_guid;
                    if ($user->logo != null) {
                        Storage::delete('public/images/users/logo/' . $user->logo);
                        $user->logo = null;
                    }
                    if ($user->background != null) {
                        Storage::delete('public/images/users/background/' . $user->background);
                        $user->background = null;
                    }
                    if ($r->description != null) {
                        $user->description = null;
                    }
                }
                if ($r->hasFile('logo')) {
                    if (file_exists('storage/images/users/logo/' . $user->logo)) {
                        if ($user->logo != null) {
                            Storage::delete('public/images/users/logo/' . $user->logo);
                        }
                    }
                    $file_name = $r->user_guid .  Str::slug(pathinfo($r->file('logo')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $r->logo->getClientOriginalExtension();
                    $r->logo->storeAs('public/images/users/logo', $file_name);
                    $user->logo = $file_name;
                }
                if ($r->hasFile('background')) {
                    if (file_exists('storage/images/users/background/' . $user->background)) {
                        if ($user->background != null) {
                            Storage::delete('public/images/users/background/' . $user->background);
                        }
                    }
                    $file_name = $r->user_guid .  Str::slug(pathinfo($r->file('background')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $r->background->getClientOriginalExtension();
                    $r->background->storeAs('public/images/users/background', $file_name);
                    $user->background = $file_name;
                }
                $user->save();
                DB::commit();
                return redirect()->route('admin.users.detail', $r->user_guid)->with('successUpdateUser', 'successUpdateUser');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function delete(Request $r)
    {
        // Deleted an user
        if (isset($r->user_guid)) {
            try {
                DB::beginTransaction();
                $delete = User::where('user_guid', $r->user_guid)->first();
                if ($delete) {
                    $delete->delete();
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
            return redirect()->back()->with('successDeletedUser', 'successDeletedUser');
        }
        return redirect()->back();
    }

    public function delete_listing(Request $r)
    {
        try {
            DB::beginTransaction();
            $delete = Listing::where('listing_guid', $r->listing_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.users.detail', $r->user_guid)->with('successDeleteUserListing', 'successUpdateUserListing');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function delete_messages(Request $r)
    {
        try {
            DB::beginTransaction();
            $user_chat_guid = UserChat::where('user_chat_guid', $r->user_chat_guid)->first();
            if ($user_chat_guid) {
                $user_chat_guid->delete();
            }
            $messages = Message::where('user_chat_guid', $r->user_chat_guid)->get();
            if ($messages) {
                foreach ($messages as $message) {
                    $message->delete();
                }
            }
            DB::commit();
            return redirect()->route('admin.users.detail', $r->user_guid)->with('successDeleteUserListing', 'successUpdateUserListing');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function status($user_guid)
    {
        try {
            $user = User::where('user_guid', $user_guid)->first();
            if ($user) {
                DB::beginTransaction();
                $user->status = 1;
                $user->save();
                DB::commit();
            } else {
                return redirect()->back()->with('errorUserStatus', 'errorUserStatus');
            }
            return redirect()->back()->with('successUserStatus', 'successUserStatus');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function unblock(Request $r)
    {
        // Unblocked user
        try {
            DB::beginTransaction();
            $delete = BlockedUser::where('user_guid', $r->owner_guid)
                ->where('blocked_user_guid', $r->blocked_guid)
                ->first();
            if ($delete) {
                $delete->delete();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back();
        }
        return redirect()->route('admin.users.detail', $r->user_guid)->with('successUnblockUser', 'successUnblockUser');
    }
}
