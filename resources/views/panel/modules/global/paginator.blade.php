@php
$total_records = $paginator->total();
$per_page = $paginator->perPage();
$last_page = $paginator->lastPage();
$current_page = $paginator->currentPage();
$has_more_pages = $paginator->hasMorePages();
$next_page_url = $paginator->nextPageUrl();
$previous_page_url = $paginator->previousPageUrl();
@endphp
@if (($total_records - $per_page) > 0)
<div>
    <ul class="pagination p-3">
        @if($current_page > 1)
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->url(1) }}">
                <i class="fa fa-angle-double-left" aria-hidden="true"></i>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="{{ $previous_page_url }}">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </a>
        </li>
        @endif
        <li class="page-item active"><a class="page-link"
                href="{{ $paginator->url($current_page) }}">{{ $current_page }}</a></li>

        @if($has_more_pages > 0)

        @for($i = $current_page;$i < $last_page; $i++) @if($i < ($current_page + 3)) <li>
            <a class="page-link" href="{{ $paginator->url($i + 1) }}">{{ $i + 1 }}</a>
            </li>
            @endif
            @endfor

            <li class="page-item">
                <a class="page-link" href="{{ $next_page_url }}">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </a>
            </li>
            @if ($current_page > 0 && $current_page != $last_page)
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($last_page) }}">
                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                </a>
            </li>
            @endif
            @endif
    </ul>
</div>
@endif
