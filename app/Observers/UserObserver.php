<?php

namespace App\Observers;

use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use App\Models\UserChat;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $listings = Listing::where('user_guid', $user->user_guid)->get();
        if ($listings) {
            foreach ($listings as $listing) {
                try {
                    DB::beginTransaction();
                    $listing->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                    return $th;
                }
            }
        }

        $user_chats = UserChat::where(function ($q) use ($user) {
            $q->orWhere('user_own_guid', $user->user_guid);
            $q->orWhere('user_opposite_guid', $user->user_guid);
        })->get();
        if ($user_chats) {
            foreach ($user_chats as $user_chat) {
                $user_messages = Message::where('user_chat_guid', $user_chat->user_chat_guid)->get();
                if ($user_messages) {
                    foreach ($user_messages as $user_message) {
                        try {
                            DB::beginTransaction();
                            $user_message->delete();
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollback();
                        }
                    }
                }
                try {
                    DB::beginTransaction();
                    $user_chat->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
