@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="kyshop-pagination">
    <style>
        .kyshop-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 0;
        }
        .kyshop-pagination a,
        .kyshop-pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 2.25rem;
            padding: 0 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s ease;
            border: 1.5px solid transparent;
        }
        .kyshop-pagination a.page-nav {
            background: #fff;
            color: #374151;
            border-color: #d1d5db;
        }
        .kyshop-pagination a.page-nav:hover {
            background: #4A0505;
            color: #fff;
            border-color: #4A0505;
            box-shadow: 0 4px 10px rgba(74,5,5,0.18);
            transform: translateY(-1px);
        }
        .kyshop-pagination span.page-nav-disabled {
            color: #d1d5db;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
    </style>

    {{-- Previous Page --}}
    @if ($paginator->onFirstPage())
        <span class="page-nav-disabled">← Prev</span>
    @else
        <a class="page-nav" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Prev</a>
    @endif

    {{-- Next Page --}}
    @if ($paginator->hasMorePages())
        <a class="page-nav" href="{{ $paginator->nextPageUrl() }}" rel="next">Next →</a>
    @else
        <span class="page-nav-disabled">Next →</span>
    @endif
</nav>
@endif
