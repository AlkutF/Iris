@if ($users->hasPages())
    <div class="pagination-wrapper">
        <ul class="pagination">
            @if ($users->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Anterior</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $users->previousPageUrl() }}" class="page-link">Anterior</a>
                </li>
            @endif

            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                </li>
            @endforeach

            @if ($users->hasMorePages())
                <li class="page-item">
                    <a href="{{ $users->nextPageUrl() }}" class="page-link">Siguiente</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Siguiente</span>
                </li>
            @endif
        </ul>
    </div>
@endif
