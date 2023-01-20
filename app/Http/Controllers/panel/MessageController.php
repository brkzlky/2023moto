<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function home(Request $r)
    {
        $d['page_title'] = 'Messages List';

        $query = UserChat::query();
        $d['users_chat'] = $query->orWhereHas('receiver', function ($q) use ($r) {
            $q->where('name', 'like', '%' . $r->search . '%');
        })->orWhereHas('sender', function ($q) use ($r) {
            $q->where('name', 'like', '%' . $r->search . '%');
        })->orWhereHas('listing_info', function ($q) use ($r) {
            $q->where('name_en', 'like', '%' . $r->search . '%')
                ->orWhere('listing_no', 'like', '%' . $r->search . '%');
        })->orWhereHas('listing_info', function ($q) use ($r) {
            $q->where('name_en', 'like', '%' . $r->search . '%')
                ->orWhere('listing_no', 'like', '%' . $r->search . '%');
        })->with('listing_info', 'messages_info', 'last_message')->paginate(10, ['*'], 'users_chat');

        $d['users_chat']->map(function ($chat) {
            $chat['user_info_own'] = User::where('user_guid', $chat->user_own_guid)->first();
            $chat['user_info_opposite'] = User::where('user_guid', $chat->user_opposite_guid)->first();
            return $chat['user_info'];
        });

        return view('panel.pages.messages', $d);
    }

    public function delete(Request $r)
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
            return redirect()->route('admin.messages.home')->with('successDeleteMessages', 'successUpdateMessages');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }
}
