<div class="c-pagi">
    <div class="c-pagi__prev" @click="goToPage(currentPage-1)">
        <span>{{ __('module/label.previouspage') }}</span>
    </div>
    <div class="c-pagi__list">
        <div class="c-pagi__list-item" :class="currentPage == 1 ? 'is-active' : null" @click="goToPage(1)">
            <span>1</span>
        </div>
        <div class="c-pagi__list-item" v-if="currentPage > 4">
            ...
        </div>
        <div class="c-pagi__list-item" :class="currentPage == p ? 'is-active' : null" v-for="p in paginationArray" @click="goToPage(p)" v-if="p > 1 && p < totalPage">
            <span>@{{ p }}</span>
        </div>
        <div class="c-pagi__list-item" v-if="currentPage < totalPage - 3">
            ...
        </div>
        <div class="c-pagi__list-item" :class="currentPage == totalPage ? 'is-active' : null" @click="goToPage(totalPage)">
            <span>@{{ totalPage }}</span>
        </div>
    </div>
    <div class="c-pagi__next" @click="goToPage(currentPage+1)">
        <span>{{ __('module/label.nextpage') }}</span>
    </div>
</div>