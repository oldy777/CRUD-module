@if ($paginator->hasPages())
    <div class="paginator">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>

        <div class="page-sizing">
            Кол-во на страницу:
            <div class="btn-group">
                <a href="{{ url()->current().'?page_size=25' }}" class="btn {{ $paginator->perPage() == 25 ? 'blue' : 'btn-default' }}">25</a>
                <a href="{{ url()->current().'?page_size=50' }}" class="btn {{ $paginator->perPage() == 50 ? 'blue' : 'btn-default' }}">50</a>
                <a href="{{ url()->current().'?page_size=100' }}" class="btn {{ $paginator->perPage() == 100 ? 'blue' : 'btn-default' }}">100</a>
            </div>
        </div>
    </div>

@endif
