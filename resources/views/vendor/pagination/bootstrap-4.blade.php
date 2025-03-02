<nav>
    <ul class="pagination justify-content-center">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link bg-primary text-white border-primary">«</span></li>
        @else
            <li class="page-item"><a class="page-link bg-primary text-white border-primary" href="{{ $paginator->previousPageUrl() }}">«</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link bg-primary text-white border-primary">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link bg-primary text-white border-primary">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link bg-primary text-white border-primary" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link bg-primary text-white border-primary" href="{{ $paginator->nextPageUrl() }}">»</a></li>
        @else
            <li class="page-item disabled"><span class="page-link bg-primary text-white border-primary">»</span></li>
        @endif
    </ul>
</nav>
