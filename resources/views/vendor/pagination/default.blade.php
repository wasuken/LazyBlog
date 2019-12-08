@if ($paginator->hasPages())
    <ul class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li><span class="pagination-previous" disabled="disabled">&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-previous">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li><span class="pagination-ellipsis">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="pagination-link is-current">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}" class="pagination-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-next">&raquo;</a></li>
        @else
            <li><span class="pagination-next" disabled="disabled">&raquo;</span></li>
        @endif
    </ul>
@endif
