<?php

namespace App\Observers;

use App\Models\Favorite;
use App\Models\Listing;
use App\Models\ListingAttribute;
use App\Models\ListingImage;
use App\Models\Message;
use App\Models\UserChat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListingObserver
{
    /**
     * Handle the Listing "created" event.
     *
     * @param  \App\Models\Listing  $listing
     * @return void
     */
    public function created(Listing $listing)
    {
        //
    }

    /**
     * Handle the Listing "updated" event.
     *
     * @param  \App\Models\Listing  $listing
     * @return void
     */
    public function updated(Listing $listing)
    {
        //
    }

    /**
     * Handle the Listing "deleted" event.
     *
     * @param  \App\Models\Listing  $listing
     * @return void
     */
    public function deleted(Listing $listing)
    {
        $favorites = Favorite::where('listing_guid', $listing->listing_guid)->get();
        if ($favorites) {
            foreach ($favorites as $favorite) {
                try {
                    DB::beginTransaction();
                    $favorite->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
        $listing_attributes = ListingAttribute::where('listing_guid', $listing->listing_guid)->get();
        if ($listing_attributes) {
            foreach ($listing_attributes as $listing_attribute) {
                try {
                    DB::beginTransaction();
                    $listing_attribute->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
        $listing_images = ListingImage::where('listing_guid', $listing->listing_guid)->get();
        if ($listing_images) {
            foreach ($listing_images as $listing_image) {
                try {
                    DB::beginTransaction();
                    // if (file_exists('storage/images/listings/' . $listing->listing_guid . '/' . $listing_image->name)) {
                    //     if ($listing_image->name != null) {
                    //         Storage::delete('public/images/listings/' . $listing->listing_guid . '/' . $listing_image->name);
                    //     }
                    // }
                    $listing_image->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
        $listing_chats = UserChat::where('listing_guid', $listing->listing_guid)->get();
        if ($listing_chats) {
            foreach ($listing_chats as $listing_chat) {
                $listing_messages = Message::where('user_chat_guid', $listing_chat->user_chat_guid)->get();
                if ($listing_messages) {
                    foreach ($listing_messages as $listing_message) {
                        try {
                            DB::beginTransaction();
                            $listing_message->delete();
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollback();
                        }
                    }
                }
                try {
                    DB::beginTransaction();
                    $listing_chat->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
    }

    /**
     * Handle the Listing "restored" event.
     *
     * @param  \App\Models\Listing  $listing
     * @return void
     */
    public function restored(Listing $listing)
    {
        //
    }

    /**
     * Handle the Listing "force deleted" event.
     *
     * @param  \App\Models\Listing  $listing
     * @return void
     */
    public function forceDeleted(Listing $listing)
    {
        //
    }
}
