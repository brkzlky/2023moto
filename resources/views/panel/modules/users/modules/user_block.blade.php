@if (count($users_block) > 0)

<div class="card-body p-0">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                <thead>

                    <tr class="text-left">
                        <th style="min-width: 140px">User Name</th>
                        <th style="min-width: 140px">Blocked User</th>
                        <th style="min-width: 140px">Blocking Time</th>
                        <th style="min-width: 180px">Unblock</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($users_block as $user_block)
                    <tr>
                        <td>
                            @if ($user_block->user_guid == $user->user_guid)
                            <span class="text-dark-75 d-block font-size-lg">{{ $user->name
                                }}</span>
                            @else
                            <span class="text-dark-75 d-block font-size-lg"><a
                                    href="{{ route('admin.users.detail',$user_block->owner->user_guid) }}">{{
                                    $user_block->owner->name }}</a></span>
                            @endif
                        </td>
                        <td>
                            @if ($user_block->user_guid != $user->user_guid)
                            <span class="text-dark-75 d-block font-size-lg">{{ $user->name
                                }}</span>
                            @else
                            <span class="text-dark-75 d-block font-size-lg"><a
                                    href="{{ route('admin.users.detail',$user_block->blocked->user_guid) }}">{{
                                    $user_block->blocked->name }}</a></span>
                            @endif
                        </td>
                        <td><span class="text-dark-75 d-block font-size-lg">{{ $user_block->created_at->diffForHumans()
                                }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.unblock') }}">
                                @csrf
                                <input type="hidden" name="owner_guid" value="{{ $user_block->owner->user_guid }}">
                                <input type="hidden" name="blocked_guid" value="{{ $user_block->blocked->user_guid}}">
                                <input type="hidden" name="user_guid" value="{{ $user->user_guid }}">
                                <button type="submit" class="btn btn-icon btn-danger">
                                    <i class="flaticon2-cross"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        @if($users_block->lastPage() > 1)
        {{
        $users_block->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$users_block])
        }}
        @endif
    </div>
</div>

@else
<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No user blocked this user</span>
<br />
@endif
