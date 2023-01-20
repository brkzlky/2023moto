<?php

namespace App\Http\Controllers\user;

use Str;
use Auth;
use Mail;
use Session;
use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use App\Models\UserChat;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendChatNotification;

class MessageController extends Controller
{
    public function __construct()
    {
        return $this->middleware("auth.member:member");
    }

    //Messages
    public function messages()
    {
        $user_guid = Auth::guard('member')->user()->user_guid;
        $d['messages'] = UserChat::where(function ($q) use ($user_guid) {
            $q->orWhere('user_own_guid', $user_guid);
            $q->orWhere('user_opposite_guid', $user_guid);
        })->with("sender", "receiver", "listing_info", "last_message")->withCount('messages_info')->get();

        $d['myguid'] = $user_guid;

        return view('user.page.messages', $d);
    }

    //Get all messages api
    public function getAllMessages(Request $r)
    {
        if (Auth::guard('member')->user()->user_guid != $r->user) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 200);
        }

        $user_guid = $r->user;
        $messages = [];

        $allmessages = UserChat::where(function ($q) use ($user_guid) {
            $q->orWhere('user_own_guid', $user_guid);
            $q->orWhere('user_opposite_guid', $user_guid);
        })->with("sender", "receiver", "listing_info.main_image", "last_message")->withCount('messages_info')->get();

        foreach ($allmessages as $ms) {
            if ($ms->user_own_guid == $user_guid && $ms->deleted_by_own == '0') {
                $messages[] = $ms;
            }

            if ($ms->user_opposite_guid == $user_guid && $ms->deleted_by_opposite == '0') {
                $messages[] = $ms;
            }
        }

        $d['messages'] = $messages;

        return response()->json($d, 200);
    }

    //Message detail api
    public function getMessages(Request $r)
    {
        if (Auth::guard('member')->user()->user_guid != $r->user) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 200);
        }

        $userguid = $r->user;
        $chatguid = $r->chat;

        $chat = UserChat::where("user_chat_guid", $chatguid)->with("sender", "receiver", "listing_info.main_image")->first();

        $status['blocked'] = 'no';
        $status['blocked_by'] = null;

        $owner = $chat->sender->user_guid !== $userguid ? $chat->sender : $chat->receiver;
        $owner['chatguid'] = $chatguid;
        if ($owner->type->id == 1) {
            $owner['usertype'] = 'standart';
        }
        if ($owner->type->id == 2) {
            $owner['usertype'] = 'commercial';
            $owner['url'] = route('site.seller_detail', ['slug' => $owner->slug . '-' . $owner->id, 'location' => Session::get('current_location')]);
        }

        $blocked = BlockedUser::where(function ($q) use ($userguid, $owner) {
            $q->where("user_guid", $userguid)->where("blocked_user_guid", $owner->user_guid);
        })->orWhere(function ($q) use ($userguid, $owner) {
            $q->where("blocked_user_guid", $userguid)->where("user_guid", $owner->user_guid);
        })->first();

        if (!is_null($blocked)) {
            if ($blocked->user_guid == $userguid) {
                $status['blocked'] = 'yes';
                $status['blocked_by'] = 'you';
            } else {
                $status['blocked'] = 'yes';
                $status['blocked_by'] = 'opposite';
            }
        }

        $messages = Message::where("user_chat_guid", $chatguid)->where(function ($q) use ($userguid) {
            $q->where("user_own_guid", $userguid);
            $q->orWhere("user_opposite_guid", $userguid);
        })->with("sender", "receiver")->get();

        $d['chatinfo']['owner'] = $owner;
        $d['chatinfo']['status'] = $status;
        $d['chatinfo']['messages'] = $messages;
        $d['chatinfo']['listing'] = $chat->listing_info;

        return response()->json($d, 200);
    }

    //Create message api
    public function createMessage(Request $r)
    {
        $user = Auth::guard('member')->user();
        $listing = Listing::where("listing_no", $r->listing)->first();
        if (is_null($listing)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.listing_not_found');
            return response()->json($d, 200);
        }

        $username = Auth::guard('member')->user()->name;

        if($user->user_guid == $r->receiver) {
            $d['result'] = 400;
            $d['msg'] = __('alert.msg_send_yourself');
            return response()->json($d, 200);
        }

        $check = UserChat::where("user_own_guid", $user->user_guid)->where("user_opposite_guid",$r->receiver)->where("listing_guid",$listing->listing_guid)->first();
        if(is_null($check)) {
            $blocked = BlockedUser::where(function($q) use ($user, $r) {
                $q->where("user_guid",$user->user_guid)->where("blocked_user_guid",$r->receiver);
            })->orWhere(function($q) use ($user, $r) {
                $q->where("blocked_user_guid",$user->user_guid)->where("user_guid",$r->receiver);

            })->first();

            if (!is_null($blocked)) {
                if ($blocked->user_guid == $user->user_guid) {
                    $d['msg'] = __('alert.you_block_msg');
                } else {
                    $d['msg'] = __('alert.you_blocked_msg');
                }
                $d['result'] = 400;
                return response()->json($d, 200);
            }

            $chat = new UserChat();
            $chat->user_chat_guid = Str::uuid();
            $chat->user_own_guid = $user->user_guid;
            $chat->user_opposite_guid = $r->receiver;
            $chat->listing_guid = $listing->listing_guid;
            $chat->save();

            $m = new Message();
            $m->message_guid = Str::uuid();
            $m->user_chat_guid = $chat->user_chat_guid;
            $m->message = $r->msg;
            $m->user_own_guid = $user->user_guid;
            $m->user_opposite_guid = $r->receiver;
            $m->send_time = date('Y-m-d H:i:s');
            $m->save();

            $receiver = User::where("user_guid",$r->receiver)->first();

            $maildata['name'] = $username;
            $maildata['listing_no'] = $listing->listing_no;
            $maildata['listing_title'] = $listing->name_en;
            $maildata['message'] = $m->message;
            Mail::to($receiver->email)->queue(new SendChatNotification($maildata));

            $d['message'] = $m;
            $d['msg'] = __('alert.message_send_success');
            $d['result'] = 200;
            return response()->json($d, 200);
        } else {
            $blocked = BlockedUser::where(function ($q) use ($user, $r) {
                $q->where("user_guid", $user->user_guid)->where("blocked_user_guid", $r->receiver);
            })->orWhere(function ($q) use ($user, $r) {
                $q->where("blocked_user_guid", $user->user_guid)->where("user_guid", $r->receiver);
            })->first();

            if (!is_null($blocked)) {
                if ($blocked->user_guid == $user->user_guid) {
                    $d['msg'] = __('alert.you_block_msg');
                } else {
                    $d['msg'] = __('alert.you_blocked_msg');
                }
                $d['result'] = 400;
                return response()->json($d, 200);
            }

            $m = new Message();
            $m->message_guid = Str::uuid();
            $m->user_chat_guid = $check->user_chat_guid;
            $m->message = $r->msg;
            $m->user_own_guid = $user->user_guid;
            $m->user_opposite_guid = $r->receiver;
            $m->send_time = date('Y-m-d H:i:s');
            $m->save();

            $receiver = User::where("user_guid",$r->receiver)->first();

            $maildata['name'] = $username;
            $maildata['listing_no'] = $listing->listing_no;
            $maildata['listing_title'] = $listing->name_en;
            $maildata['message'] = $m->message;
            Mail::to($receiver->email)->queue(new SendChatNotification($maildata));

            $d['message'] = $m;
            $d['msg'] = __('alert.message_send_success');
            $d['result'] = 200;
            return response()->json($d, 200);
        }
    }

    //Send message api
    public function sendMessage(Request $r)
    {
        if (Auth::guard('member')->user()->user_guid != $r->user) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 200);
        }

        $username = Auth::guard('member')->user()->name;

        $userguid = $r->user;
        $chatguid = $r->chat;
        $msg = $r->msg;

        $chat = UserChat::where("user_chat_guid", $chatguid)->with("sender", "receiver")->first();
        $owner = $chat->sender->user_guid !== $userguid ? $chat->sender : $chat->receiver;

        $blocked = BlockedUser::where(function ($q) use ($userguid, $owner) {
            $q->where("user_guid", $userguid)->where("blocked_user_guid", $owner->user_guid);
        })->orWhere(function ($q) use ($userguid, $owner) {
            $q->where("blocked_user_guid", $userguid)->where("user_guid", $owner->user_guid);
        })->first();

        if (!is_null($blocked)) {
            if ($blocked->user_guid == $userguid) {
                $d['msg'] = __('alert.you_block_msg');
            } else {
                $d['msg'] = __('alert.you_blocked_msg');
            }
            $d['result'] = 400;
            return response()->json($d, 200);
        }

        $m = new Message();
        $m->message_guid = Str::uuid();
        $m->user_chat_guid = $chatguid;
        $m->message = $msg;
        $m->user_own_guid = $userguid;
        $m->user_opposite_guid = $owner->user_guid;
        $m->send_time = date('Y-m-d H:i:s');
        $m->save();

        $receiver = User::where("user_guid",$owner->user_guid)->first();
        $listing = Listing::where("listing_guid", $chat->listing_guid)->first();

        $maildata['name'] = $username;
        $maildata['listing_no'] = $listing->listing_no;
        $maildata['listing_title'] = $listing->name_en;
        $maildata['message'] = $m->message;
        Mail::to($receiver->email)->queue(new SendChatNotification($maildata));

        $d['message'] = $m;
        $d['msg'] = __('alert.message_send_success');
        $d['result'] = 200;

        return response()->json($d, 200);
    }

    //Delete messages api
    public function deleteMessage(Request $r)
    {

        if (Auth::guard('member')->user()->user_guid != $r->user) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 200);
        }

        $userguid = $r->user;
        $chatguid = $r->chat;

        $chat = UserChat::where("user_chat_guid", $chatguid)->with("sender", "receiver")->first();
        if (is_null($chat)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.conversation_delete_error');
            return response()->json($d, 200);
        } else {
            if ($chat->user_own_guid == $userguid) {
                $chat->deleted_by_own = '1';
            } else {
                $chat->deleted_by_opposite = '1';
            }

            $chat->update();
            $d['result'] = 200;
            $d['msg'] = __('alert.conversation_delete_success');
            return response()->json($d, 200);
        }
    }

    //Block message user api
    public function blockUser(Request $r)
    {
        if (Auth::guard('member')->user()->user_guid != $r->user) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 200);
        }

        $userguid = $r->user;
        $oppositeuser = $r->opposite;
        $blocked = BlockedUser::where("user_guid", $userguid)->orWhere("blocked_user_guid", $userguid)->first();
        if (is_null($blocked)) {
            $bl = new BlockedUser();
            $bl->user_guid = $userguid;
            $bl->blocked_user_guid = $oppositeuser;
            $bl->save();

            $d['result'] = 200;
            $d['msg'] = __('alert.block_success');
            return response()->json($d, 200);
        } else {
            $d['result'] = 400;
            $d['msg'] = __('alert.block_error');
            return response()->json($d, 200);
        }
    }

    //Unblock message user api
    public function unblockUser(Request $r)
    {
        if (Auth::guard('member')->user()->user_guid != $r->user) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 200);
        }

        $userguid = $r->user;
        $oppositeuser = $r->opposite;

        $blocked = BlockedUser::where("user_guid", $userguid)->where("blocked_user_guid", $oppositeuser)->first();
        if (!is_null($blocked)) {
            $blocked->delete();

            $d['result'] = 200;
            $d['msg'] = __('alert.unblock_success');
            return response()->json($d, 200);
        } else {
            $d['result'] = 400;
            $d['msg'] = 'You are not authorized for this action.';
            return response()->json($d, 200);
        }
    }
}
