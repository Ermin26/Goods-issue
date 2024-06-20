@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="pagination-item disabled">
                <span class="pagination-link">&laquo;</span>
            </li>
        @else
            <li class="pagination-item">
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev">&laquo;</a>
            </li>

        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Array Of Links --}}
            @if (is_array($element))
                {{-- First Page --}}

                {{-- "Three Dots" Separator --}}
                @if (isset($element['separator']))
                    <li class="pagination-item disabled">
                        <span class="pagination-link">{{ $element['separator'] }}</span>
                    </li>
                @endif

                {{-- Pages --}}
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination-item active">
                            <span class="pagination-link">{{ $page }}</span>
                        </li>

                    @elseif ($page > $paginator->currentPage() - 2 && $page < $paginator->currentPage() + 2)
                        <li class="pagination-item">
                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                        </li>
                    @elseif ($page > $paginator->currentPage() - 3 && $page < $paginator->currentPage() + 3)
                        <li class="pagination-item">
                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                        </li>
                    @elseif ($page == $paginator->lastPage())
                    <li class="pagination-item">
                        <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                    </li>
                    @endif
                @endforeach

                {{-- Last Page --}}
                
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="pagination-item">
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next">&raquo;</a>
            </li>
        @else
            <li class="pagination-item disabled">
                <span class="pagination-link">&raquo;</span>
            </li>
        @endif
    </ul>
@endif
