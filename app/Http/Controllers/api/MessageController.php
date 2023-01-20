<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\SendChatNotification;
use App\Models\BlockedUser;
use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use App\Models\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Str;
use Mail;
use OneSignal;

class MessageController extends Controller
{
    /**
     * @OA\Post(
     *  path="api.motovago.com/getMessages",
     *  operationId="messages",
     *  summary="Gets all messages of authed user.User must be logged in !",
     *    @OA\Parameter(
     *    description="User's Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="a6b55537c77beca81628231e3682d13c",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="user_chat_guid", type="string", example="03d6cbbb-a43f-4143-8f9c-288485de12e4"),
     *       @OA\Property(property="user_own_guid", type="string", example="03d6cbbb-a43f-4143-8f9c-288485de12e4"),
     *       @OA\Property(property="user_opposite_guid", type="string", example="03d6cbbb-a43f-4143-8f9c-288485de12e4"),
     *       @OA\Property(property="listing_guid", type="string", example="03d6cbbb-a43f-4143-8f9c-288485de12e4"),
     *       @OA\Property(property="deleted_by_own", type="string", example="0"),
     *       @OA\Property(property="deleted_by_opposite", type="string", example="0"),
     *       @OA\Property(property="created_at", type="string", example="2021-10-12T07:39:33.000000Z"),
     *       @OA\Property(property="updated_at", type="string", example="2021-10-12T07:39:33.000000Z"),
     *       @OA\Property(property="deleted_at", type="string", example="2021-10-12T07:39:33.000000Z"),
     *       @OA\Property(property="messages_info_count", type="string", example="8"),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="User is not logged in."),
     *        )
     *     )
     * )
     *
     */
    public function getMessages(Request $r)
    {
        $token = $r->token;
        $user = User::Where('token', $token)->where('status', '1')->first();

        if (is_null($user)) {
            $d['status'] = 400;
            $d['msg'] = 'User is not logged in';
            return response()->json($d, 400);
        }
        $user_guid = $user->user_guid;

        $message_list = [];
        $messages = UserChat::where(function ($q) use ($user_guid) {
            $q->orWhere('user_own_guid', $user_guid);
            $q->orWhere('user_opposite_guid', $user_guid);
        })->with("sender", "receiver", "listing_info", "last_message")->withCount('messages_info')->get();
        foreach ($messages as $m) {
            if (!is_null($m->receiver->logo)) {
                $m['receiver']['logo'] = config('api.main_url') . '/storage/user/' . $m->receiver->logo;
            }

            if($m->user_own_guid == $user_guid) {
                if($m->deleted_by_own == 0) {
                    $message_list[] = $m;
                }
            }

            if($m->user_opposite_guid == $user_guid) {
                if($m->deleted_by_opposite == 0) {
                    $message_list[] = $m;
                }
            }
        }

        $d['myguid'] = $user_guid;

        //$d['messages'] = $messages;
        $d['messages'] = $message_list;
        return response()->json($d, 200);
    }
    /**
     * @OA\Post(
     *  path="api.motovago.com/getMessagesDetail",
     *  operationId="messages_detail",
     *  summary="Gets messages when clicked from chat list.User must be logged in !",
     *
     * @OA\Parameter(
     *    description="Chat's guid",
     *    in="query",
     *    name="chat",
     *    required=true,
     *    example="54cddd50-8369-4255-bfbf-3b30c9e49f89",
     * ),
     *  @OA\Parameter(
     *    description="User's Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f261b5ee8d2517d9c04670abecca99b",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *     @OA\Property(property="chatinfo", type="array",
     * @OA\Items(type="object",
     * format="query",
     *      @OA\Property(property="owner", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="user_guid", type="string", example="123123123-1414123-124123"),
     *       @OA\Property(property="type_guid", type="string", example="123123123-1414123-124123"),
     *       @OA\Property(property="country_guid", type="string", example="123123123-1414123-124123"),
     *       @OA\Property(property="name", type="string", example="Yigit Bayol"),
     *       @OA\Property(property="slug", type="string", example="yigit-bayol"),
     *       @OA\Property(property="phone", type="string", example="5543231212"),
     *       @OA\Property(property="email", type="string", example="yigit@appricot.com.tr"),
     *   @OA\Property(property="type", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="type_guid", type="string", example="123123123-12312312-3123123"),
     *       @OA\Property(property="name_en", type="string", example="Standart"),
     *       @OA\Property(property="name_ar", type="string", example="اساسي"),
     *       @OA\Property(property="free_listing", type="string", example="3"),
     *       @OA\Property(property="free_listing_period", type="string", example="m"),
     *       @OA\Property(property="status", type="string", example="1"),
     *
     *
     * )),
     * )),
     * @OA\Property(property="status", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="blocked", type="string", example="no"),
     *       @OA\Property(property="blocked_by", type="string", example="null"),
     *
     * )),
     * @OA\Property(property="messages", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="string", example="17"),
     *       @OA\Property(property="message_guid", type="string", example="3c8a2b85-8e5a-4e2f-bfa1-a1edb31ce538"),
     *       @OA\Property(property="user_chat_guid", type="string", example="3c8a2b85-8e5a-4e2f-bfa1-a1edb31ce538"),
     *       @OA\Property(property="user_own_guid", type="string", example="3c8a2b85-8e5a-4e2f-bfa1-a1edb31ce538"),
     *       @OA\Property(property="user_opposite_guid", type="string", example="3c8a2b85-8e5a-4e2f-bfa1-a1edb31ce538"),
     *       @OA\Property(property="is_me", type="string", example="yes"),
     *       @OA\Property(property="message", type="string", example="naber"),
     *  @OA\Property(property="send_time", type="datetime", example="2021-10-21 14:38:32"),
     *   @OA\Property(property="sender", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="datetime", example="1"),
     *       @OA\Property(property="user_guid", type="datetime", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="type_guid", type="datetime", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="country_guid", type="datetime", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="name", type="datetime", example="Yigit Bayol"),
     *       @OA\Property(property="slug", type="datetime", example="yigit-bayol"),
     *       @OA\Property(property="phone", type="datetime", example="5543422312"),
     *
     * )),
     *    @OA\Property(property="receiver", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="user_guid", type="string", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="type_guid", type="string", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="country_guid", type="string", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="name", type="string", example="Yigit Bayol"),
     *       @OA\Property(property="slug", type="string", example="yigit-bayol"),
     *       @OA\Property(property="phone", type="string", example="5543422312"),
     *
     * )))),
     * @OA\Property(property="listing", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="listing_guid", type="string", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="user_guid", type="string", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="location_guid", type="string", example="8192fb11-a309-465b-9e18-b616683dab3b"),
     *       @OA\Property(property="listing_no", type="string", example="47382643"),
     *
     * ))
     *      ))  )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Unauthorized user."),
     *       ) ),
     *      @OA\Response(
     *    response=404,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="404"),
     *       @OA\Property(property="msg", type="string", example="User not found."),
     *        )
     *     )
     * )
     *
     */
    public function getMessagesDetail(Request $r)
    {


        $usr_check = User::where('token', $r->token)->first();
        if (!is_null($usr_check) && $usr_check->status == 1) {
            $userguid = $usr_check->user_guid;
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
            foreach ($messages as $m) {
                if ($m->user_own_guid == $usr_check->user_guid) {
                    $m['is_me'] = 'yes';

                } else {
                    $m['is_me'] = 'no';

                }
            }
            if (!is_null($chat->listing_info->main_image)) {
                $chat->listing_info->main_image->name = config('api.main_url') . '/storage/listings/' . $chat->listing_info->listing_no . '/' . $chat->listing_info->main_image->name;
            }
            $d['chatinfo']['owner'] = $owner;
            $d['chatinfo']['status'] = $status;
            $d['chatinfo']['messages'] = $messages;
            $d['chatinfo']['listing'] = $chat->listing_info;
            return response()->json($d, 200);
        } elseif ($usr_check->status == 0) {
            $d['status'] = 400;
            $d['msg'] = "Unauthorized user!";
            return response()->json($d, 400);
        } else {
            $d['status'] = 404;
            $d['msg'] = "User not found";
            return response()->json($d, 404);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/createMessage",
     *  operationId="CreateChatfromListing",
     *  summary="Creates a chat with a user from listing detail!",
     *
     * @OA\Parameter(
     *    description="Receiver",
     *    in="query",
     *    name="receiver",
     *    required=true,
     *    example="54cddd50-8369-4255-bfbf-3b30c9e49f89",
     * ),
     *  @OA\Parameter(
     *    description="listing_guid",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="54cddd50-8369-4255-bfbf-3b30c9e49f89",
     * ),
     *    @OA\Parameter(
     *    description="msg",
     *    in="query",
     *    name="msg",
     *    required=true,
     *    example="Hello how much is it.",
     * ),
     *    @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f261b5ee8d2517d9c04670abecca99b",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Message sended successfully"),
     *
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="User doesnt exist. & Listing doesn't exist."),
     *        )
     *     )
     * )
     *
     */
    public function createMessage(Request $r)
    {
        $user = User::where('token', $r->token)->first();
        if (is_null($user)) {
            $d['status'] = 400;
            $d['msg'] = "User doesn't exist.";
            return response()->json($d, 400);
        }
        $listing = Listing::where("listing_guid", $r->listing_guid)->first();
        if (is_null($listing)) {
            $error = "Listing doesn't exists.";
            return response()->json($error, 400);
        }

        $check = UserChat::where("user_own_guid", $user->user_guid)->where("user_opposite_guid", $r->receiver)->where("listing_guid", $listing->listing_guid)->first();
        if (is_null($check)) {
            $blocked = BlockedUser::where(function ($q) use ($user, $r) {
                $q->where("user_guid", $user->user_guid)->where("blocked_user_guid", $r->receiver);
            })->orWhere(function ($q) use ($user, $r) {
                $q->where("blocked_user_guid", $user->user_guid)->where("user_guid", $r->receiver);
            })->first();

            if (!is_null($blocked)) {
                if ($blocked->user_guid == $user->user_guid) {
                    $error = "You blocked message";
                    return response()->json($error, 400);
                } else {
                    $d['status'] = 400;
                    $d['msg'] = "You blocked from user.";
                    return response()->json($d, 400);
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

            $type['type'] = 'messages';
            OneSignal::sendNotificationUsingTags(
                "You have a message.",
                array(
                    ["field" => "tag", "key" => "user_guid", "relation" => "=", "value" => $r->receiver]
                ),
                $url = null,
                $data = $type,
                $buttons = null,
                $schedule = null
            );
            $d['status'] = 200;
            $d['msg'] = "Message sended successfully.";
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

            if($check->user_own_guid == $user->user_guid) {
                $check->deleted_by_own = '0';
            }
            $check->update();

            if ($check->deleted_by_own != '1' && $check->deleted_by_opposite != '1') {
                $m = new Message();
                $m->message_guid = Str::uuid();
                $m->user_chat_guid = $check->user_chat_guid;
                $m->message = $r->msg;
                $m->user_own_guid = $user->user_guid;
                $m->user_opposite_guid = $r->receiver;
                $m->send_time = date('Y-m-d H:i:s');
                $m->save();

                $type['type'] = 'messages';
                OneSignal::sendNotificationUsingTags(
                    "You have a message.",
                    array(
                        ["field" => "tag", "key" => "user_guid", "relation" => "=", "value" => $r->receiver]
                    ),
                    $url = null,
                    $data = $type,
                    $buttons = null,
                    $schedule = null
                );

                $d['msg'] = "Message sended successfully.";
                $d['status'] = 200;
                return response()->json($d, 200);
            }

            $userReceiver = User::where('user_guid', $r->receiver)->first();
            $d['msg'] = "This message has been deleted by ".$userReceiver->name." You cannot send any more messages.";
            $d['status'] = 200;
            return response()->json($d, 200);

        }
    }
    /**
     * @OA\Post(
     *  path="api.motovago.com/sendMessage",
     *  operationId="SendMessageToUserChatAlreadyExist",
     *  summary="Sends a message to a user which already chat created.",
     *
     * @OA\Parameter(
     *    description="Receiver User",
     *    in="query",
     *    name="user",
     *    required=true,
     *    example="50848090-11f7-4580-a937-b20a85a09c13",
     * ),
     *  @OA\Parameter(
     *    description="chat",
     *    in="query",
     *    name="chat",
     *    required=true,
     *    example="60303670",
     * ),
     *    @OA\Parameter(
     *    description="msg",
     *    in="query",
     *    name="msg",
     *    required=true,
     *    example="Hello how much is it.",
     * ),
     *    @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f261b5ee8d2517d9c04670abecca99b",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Message sended successfully"),
     *
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="User doesnt exist. & Chat doesn't exist. & User is restricted & You blocked & You blocked user."),
     *        )
     *     )
     * )
     *
     */
    public function sendMessage(Request $r)
    {

        $user_check = User::where('token', $r->token)->first();
        if (is_null($user_check)) {
            $d['status'] = 400;
            $d['msg'] = "User doesn't exist";
            return response()->json($d, 400);
        } elseif ($user_check->status != 1) {
            $d['status'] = 400;
            $d['msg'] = "User is restricted";
            return response()->json($d, 400);
        }

        $username = $user_check->name;
        $userguid = $user_check->user_guid;
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
                $d['status'] = 400;
                $d['msg'] = "You blocked messages";
                return response()->json($d, 400);
            } else {
                $d['status'] = 400;
                $d['msg'] = "You blocked by user";
                return response()->json($d, 400);
            }
        }

        if ($chat->deleted_by_own != '1' && $chat->deleted_by_opposite != '1') {
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
            // Mail::to($receiver->email)->queue(new SendChatNotification($maildata));

            $type['type'] = 'messages';
            OneSignal::sendNotificationUsingTags(
                "You have a message.",
                array(
                    ["field" => "tag", "key" => "user_guid", "relation" => "=", "value" => $receiver->user_guid]
                ),
                $url = null,
                $data = $type,
                $buttons = null,
                $schedule = null
            );
            $d['status'] = 200;
            $d['msg'] = "Your message sended successfully.";
            return response()->json($d, 200);
        }

        $d['msg'] = "This message has been deleted. You cannot send any more messages.";
        $d['status'] = 400;
        return response()->json($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/deleteMessage",
     *  operationId="Delete Message Box",
     *  summary="Deletes messagebox which selected.",
     *
     * @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="50848090-11f7-4580-a937-b20a85a09c13",
     * ),
     *  @OA\Parameter(
     *    description="chat",
     *    in="query",
     *    name="chat",
     *    required=true,
     *    example="60303670",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Message sended successfully."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="User doesnt exist. & Chat doesn't exist. & User is restricted & You blocked & You blocked user."),
     *        )
     *     )
     * )
     *
     */


    public function deleteMessage(Request $r)
    {
        $user_check = User::where('token', $r->token)->first();
        if ($user_check == null) {
            $d['status'] = 400;
            $d['msg'] = "Unauthorized user.";
            return response()->json($d, 400);
        }
        $userguid = $user_check->user_guid;
        $chatguid = $r->chat;

        $chat = UserChat::where("user_chat_guid", $chatguid)->with("sender", "receiver")->first();
        if (is_null($chat)) {
            $d['status'] = 400;
            $d['msg'] = "There is an error occurred while deleting chat. Please try again.";
            return response()->json($d, 400);
        } else {
            if ($chat->user_own_guid == $userguid) {
                $chat->deleted_by_own = '1';
            } else if($chat->user_opposite_guid == $userguid) {
                $chat->deleted_by_opposite = '1';
            } else {
                $d['status'] = 400;
                $d['msg'] = "You don't have a permission to delete this conversation!";
                return response()->json($d, 400);
            }


            $chat->update();
            $d['status'] = 200;
            $d['msg'] = "Chat deleted successfully.";
            return response()->json($d, 200);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/blockUser",
     *  operationId="Block User Message",
     *  summary="Block User Message.",
     *
     * @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="50848090-11f7-4580-a937-b20a85a09c13",
     * ),
     *  @OA\Parameter(
     *    description="opposite",
     *    in="query",
     *    name="opposite",
     *    required=true,
     *    example="50848090-11f7-4580-a937-b20a85a09c13",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="User blocked successfully"),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="User doesnt exist. & There is a problem occurred while blocking user please try again."),
     *        )
     *     )
     * )
     *
     */

    public function blockUser(Request $r)
    {
        $user_check = User::where('token', $r->token)->first();
        if (is_null($user_check)) {
            $d['msg'] = "User not found.";
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $oppositeuser = $r->opposite;
        $blocked = BlockedUser::where("user_guid", $user_check->user_guid)->orWhere("blocked_user_guid", $user_check->user_guid)->first();
        if (is_null($blocked)) {
            $bl = new BlockedUser();
            $bl->user_guid = $user_check->user_guid;
            $bl->blocked_user_guid = $oppositeuser;
            $bl->save();

            $d['status'] = 200;
            $d['msg'] = "User blocked successfully.";
            return response()->json($d, 200);
        } else {
            $d['status'] = 400;
            $d['msg'] = "There is a problem occurred while blocking user please try again.";
            return response()->json($d, 400);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/unblockUser",
     *  operationId="unBlock User Message",
     *  summary="unBlock User Message.",
     *
     * @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="50848090-11f7-4580-a937-b20a85a09c13",
     * ),
     *  @OA\Parameter(
     *    description="opposite",
     *    in="query",
     *    name="opposite",
     *    required=true,
     *    example="50848090-11f7-4580-a937-b20a85a09c13",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="User unblocked successfully"),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="User doesnt exist. & There is a problem occurred while unblocking user please try again."),
     *        )
     *     )
     * )
     *
     */
    public function unblockUser(Request $r)
    {
        $user_check = User::where('token', $r->token)->first();
        if ($user_check == null) {
            $d['msg'] = "Unauthorized user.";
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $oppositeuser = $r->opposite;

        $blocked = BlockedUser::where("user_guid", $user_check->user_guid)->where("blocked_user_guid", $oppositeuser)->first();
        if (!is_null($blocked)) {
            $blocked->delete();
            $d['status'] = 200;
            $d['msg'] = "User unblocked successfully.";
            return response()->json($d, 200);
        } else {
            $d['status'] = 400;
            $d['msg'] = 'You are not authorized for this action.';
            return response()->json($d, 400);
        }
    }
}
