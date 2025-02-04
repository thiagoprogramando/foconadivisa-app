@if ($paginator->hasPages())
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-warning">
            PULAR
        </a>
    @endif
@endif
