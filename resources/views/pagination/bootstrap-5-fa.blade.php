@if ($paginator->hasPages())
    <nav aria-label="صفحه‌بندی">
        <ul class="pagination justify-content-center">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">قبلی</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">قبلی</a>
                </li>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">...</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ \App\Support\JalaliDate::toPersianDigits($page) }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ \App\Support\JalaliDate::toPersianDigits($page) }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">بعدی</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">بعدی</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
