@extends('site.layout.master')

@section('main_content')
<section class="c-section c-section--md-top c-section--msg" id="messages"> 
    <input type="hidden" id="mgd" value="{{ $myguid }}">
    <input type="hidden" id="lang" value="{{ Session::get('current_language') }}">
    <input type="hidden" id="delete_msg" value="{{ __('alert.conversation_delete_msg') }}">
    <div class="container c-msg__container h-100">
        <div class="row d-none d-sm-none d-md-none d-lg-block">
            <div class="col-lg-12">
                <div class="c-msg__head">
                    <!-- page title -->
                    <div class="c-title">
                        <p class="c-title__desc c-title__desc--uppercase c-title__desc--top">{{ __('user/messages.toptitle') }}</p>
                        <h3 class="c-title__heading  c-title__heading--regular">{{ __('user/messages.title') }}
                        </h3>
                    </div>
                    <!-- search msgs -->
                    <div class="c-input--air__wrapper c-input--air__wrapper-has-icon c-msg__search">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#search') }}"></use>
                        </svg>
                        <input type="search" class="c-input c-input--air" placeholder="{{ __('user/messages.search') }}" @keyup="searchMessage($event)">
                    </div>
                </div>
            </div>
        </div>

        <div class="c-msg__wrapper" style="height: calc(100% - 103px);" v-if="messages.length > 0">
            <div class="c-msg__left">
                <div class="d-flex d-sm-flex d-md-flex d-lg-none justify-content-between align-items-start">
                    <div class="c-title">
                        <p class="c-title__desc c-title__desc--uppercase c-title__desc--top">{{ __('user/messages.toptitle') }}</p>
                        <h3 class="c-title__heading  c-title__heading--regular">{{ __('user/messages.title') }}
                        </h3>
                    </div>
                    <!-- search msgs -->
                    <div class="c-input--air__wrapper c-input--air__wrapper-has-icon c-msg__search">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#search') }}"></use>
                        </svg>
                        <input type="search" class="c-input c-input--air" placeholder="{{ __('user/messages.search') }}">
                    </div>
                </div>
                <!-- page title -->
                <div class="c-msg__box" :class="m.user_chat_guid == selectedMessage ? 'is-active' : null" v-for="m in messages">
                    <a class="c-msg__box-link" href="javascript:;" @click="openMessage(m.user_chat_guid)">
                        <div class="c-msg__box-img" v-if="m.listing_info?.main_image == null">
                            <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" alt="">
                        </div>
                        <div class="c-msg__box-img" v-if="m.listing_info?.main_image !== null">
                            <img :src="window.location.origin+'/storage/listings/'+m.listing_info?.listing_no+'/'+m.listing_info?.main_image?.name" alt="">
                        </div>
                        <div class="c-msg__box-detail">
                            <p class="c-msg__box-title">@{{ lang == 'ar' ? m.listing_info.name_ar : m.listing_info.name_en }}</p>
                            <p class="c-msg__box-info">
                                <span>
                                    <span>@{{ getMsgDate(m.last_message.send_time) }}</span>
                                    <span>@{{ getMsgTime(m.last_message.send_time) }}</span>
                                </span>

                            </p>
                        </div>
                    </a>
                    <div class="c-msg__box-actions">
                        <div class="c-msg__box-badge">@{{ m.messages_info_count }}</div>
                    </div>
                </div>
            </div>
            <div class="c-msg__right is-hidden">
                <div class="c-msg__box" v-if="messageInfo != null">
                    <a class="c-msg__box-link" href="#" >
                        <div class="c-msg__box-img" v-if="messageInfo.listing?.main_image == null">
                            <img src="{{ secure_asset('site/assets/images/no-listing.png') }}" alt="">
                        </div>
                        <div class="c-msg__box-img" v-if="messageInfo.listing?.main_image !== null">
                            <img :src="window.location.origin+'/storage/listings/'+messageInfo.listing?.listing_no+'/'+messageInfo.listing?.main_image?.name" alt="">
                        </div>
                        <div class="c-msg__box-detail">
                            <p class="c-msg__box-title">@{{ lang == 'ar' ? messageInfo.listing.name_ar : messageInfo.listing.name_en }}</p>
                            <p class="c-msg__box-info">
                                <span>
                                    <span>@{{ getMsgDate(messageInfo.messages[0].send_time) }}</span>
                                    <span>@{{ getMsgTime(messageInfo.messages[0].send_time) }}</span>
                                </span>

                            </p>
                        </div>
                    </a>
                </div>
                <div class="c-msg__right-head" v-if="messageInfo != null">
                    <div class="c-msg__right-head-info">
                        <div class="c-msg__right-back" @click="closeMessageInfo()">
                            <svg class="c-icon__svg c-icon--sm">
                                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#chevron-left') }}"></use>
                            </svg>
                        </div>
                        <p class="c-msg__right-name">@{{ messageInfo?.owner.name }}</p>
                        <p class="c-msg__right-tel">@{{ messageInfo?.owner.phone }}</p>
                    </div>
                    <div class="c-msg__more dropdown-toggle" aria-haspopup="true" aria-expanded="false" v-if="messageInfo.status.blocked == 'no' || (messageInfo.status.blocked == 'yes' && messageInfo.status.blocked_by == 'you')">
                        <svg class="c-icon__svg c-icon--lg">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#more') }}"></use>
                        </svg>
                        <div class="dropdown-menu dropdown-menu--more dropdown-menu-end" aria-labelledby="search">
                            <div class="dropdown-menu--more__body">
                                <span href="#" @click="deleteMessage(messageInfo)" class="dropdown-menu--more-item">
                                    {{ __('user/messages.delete_messages') }}
                                </span>
                                <span href="#" @click="blockUser(messageInfo)" class="dropdown-menu--more-item" v-if="messageInfo.status.blocked == 'no'">
                                    {{ __('user/messages.block_user') }}
                                </span>
                                <span href="#" @click="unblockUser(messageInfo)" class="dropdown-menu--more-item" v-if="messageInfo.status.blocked == 'yes' && messageInfo.status.blocked_by == 'you'">
                                    {{ __('user/messages.unblock_user') }}
                                </span>
                                <span @click="userProfile(messageInfo.owner)" class="dropdown-menu--more-item" v-if="messageInfo.owner.usertype == 'commercial'">
                                    {{ __('user/messages.view_profile') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alertbox">@{{ alertMsg }}</div>
                <div class="c-msg__right-body" id="c-msg__right-body" v-if="messageInfo == null"></div>
                <div class="c-msg__right-body" id="c-msg__right-body" v-if="messageInfo != null">
                    <div v-if="messageInfo.messages.length > 0">
                        <div :class="m.user_own_guid != userguid ? 'c-msg__incoming' : 'c-msg__sent'" v-for="m in messageInfo?.messages">
                            <div :class="m.user_own_guid != userguid ? 'c-msg__incoming-content' : 'c-msg__sent-content'">
                                @{{ m.message }}
    
                                <span>@{{ getMsgDateTime(m.send_time) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="c-msg__right-input" v-if="messageInfo != null && messageInfo.status.blocked == 'no'">
                    <input type="text" placeholder="{{ __('user/messages.type_something') }}" v-model="newMessage">
                    <button class="c-button c-button--black c-button--sm c-button--uppercase c-button--xl-w" @click="sendMessage(messageInfo)">{{ __('module/button.send') }}</button>
                </div>
            </div>
        </div>

        <div class="c-empty">
            <div class="c-empty__head">
                <svg class="c-icon__svg c-icon--xl">
                    <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#bubble-lined') }}"></use>
                </svg>
            </div>
            <div class="c-empty__body">
                {!! __('alert.dont_have_messages') !!}
            </div>
        </div>
    </div>

    <div class="c-modal modal fade" id="deleteMsgModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="c-modal__dialog modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="c-modal__content modal-content">
                <div class="c-modal__header modal-header">
                    <h5 class="c-modal__title modal-title" id="exampleModalLabel">{{ __('module/label.delete_conversation_title') }}</h5>
                    <button type="button" class="c-button__close btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="c-modal__body modal-body" v-html="deleteMsgText">
                </div>
                <div class="c-modal__footer modal-footer">
                    <button @click="deleteApproved()" class="c-button c-button--black c-button--uppercase c-button--11 c-button--sm c-button--lg-w">
                        {{ __('module/button.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="{{ secure_asset('site/vue/members/messages.js') }}"></script>
@endsection