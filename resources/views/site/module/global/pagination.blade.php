@php
    $pagination = $paginator->toArray();
    $total_records = $pagination['total'];
    $per_page = $pagination['per_page'];
    $last_page = $pagination['last_page'];
    $current_page = $pagination['current_page'];
    $next_page_url = $pagination['next_page_url'];
    $previous_page_url = $pagination['prev_page_url'];
    $first_page_url = $pagination['first_page_url'];
    $last_page_url = $pagination['last_page_url'];
    $page_url = $pagination['path'];
@endphp
@if($last_page > 1)
<div class="col-12">
    <div class="c-pagi">
        <div class="c-pagi__prev">
            @if($current_page != 1)
            <a href="{{ $previous_page_url }}">{{ __('module/label.previouspage') }}</a>
            @else
            <a href="javascript:;">{{ __('module/label.previouspage') }}</a>
            @endif
        </div>
        <div class="c-pagi__list">
            <div class="c-pagi__list-item {{ $current_page == 1 ? 'is-active' : null }}">
                <a href="{{ $first_page_url }}">1</a>
            </div>
            @if($current_page > 4)
            <div class="c-pagi__list-item">
                <a href="javascript:;">...</a>
            </div> 
            @endif
            @for ($i = 2; $i < $last_page; $i++)
                @if($last_page < 10)
                    <div class="c-pagi__list-item {{ $current_page == $i ? 'is-active' : null }}">
                        <a href="{{ $page_url.'?page='.$i }}">{{ $i }}</a>
                    </div>
                @else
                    @if($i < $current_page + 3 && $i > $current_page - 3)
                    <div class="c-pagi__list-item {{ $current_page == $i ? 'is-active' : null }}">
                        <a href="{{ $page_url.'?page='.$i }}">{{ $i }}</a>
                    </div>
                    @endif
                @endif
            @endfor
            @if($current_page < ($last_page - 3))
            <div class="c-pagi__list-item">
                <a href="javascript:;">...</a>
            </div> 
            @endif
            @if($last_page != 1)
            <div class="c-pagi__list-item  {{ $current_page == $last_page ? 'is-active' : null }}">
                <a href="{{ $last_page_url }}">{{ $last_page }}</a>
            </div>
            @endif
        </div>
        <div class="c-pagi__next">
            @if($current_page != $last_page)
            <a href="{{ $next_page_url }}">{{ __('module/label.nextpage') }}</a>
            @else
            <a href="javascript:;">{{ __('module/label.nextpage') }}</a>
            @endif
        </div>
    </div>
</div>
@endif