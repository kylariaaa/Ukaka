@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="admin-pagination">
    <style>
        .admin-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0 0.25rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .admin-pagination-left {
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
        }
        .admin-pagination-left span {
            font-weight: 700;
            color: #111827;
        }
        .admin-pagination-right {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        .admin-pagination a,
        .admin-pagination .apg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            height: 2rem;
            padding: 0 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            border: 1.5px solid transparent;
            cursor: pointer;
            user-select: none;
            line-height: 1;
        }
        /* Inactive page links */
        .admin-pagination a.apg-page {
            color: #374151;
            background: #ffffff;
            border-color: #d1d5db;
        }
        .admin-pagination a.apg-page:hover {
            background: #4A0505;
            color: #fff;
            border-color: #4A0505;
            box-shadow: 0 3px 8px rgba(74,5,5,0.20);
            transform: translateY(-1px);
        }
        /* Active page */
        .admin-pagination .apg-active {
            background: linear-gradient(135deg, #4A0505 0%, #7c1111 100%);
            color: #fff !important;
            border-color: transparent !important;
            box-shadow: 0 3px 10px rgba(74,5,5,0.35);
            cursor: default;
        }
        /* Disabled (prev/next) */
        .admin-pagination .apg-disabled {
            color: #c4c4c4;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        /* Prev / Next arrows */
        .admin-pagination a.apg-nav {
            padding: 0 0.75rem;
            background: #ffffff;
            color: #374151;
            border-color: #d1d5db;
            font-size: 0.8rem;
            gap: 0.2rem;
        }
        .admin-pagination a.apg-nav:hover {
            background: #4A0505;
            color: #fff;
            border-color: #4A0505;
            box-shadow: 0 3px 8px rgba(74,5,5,0.20);
            transform: translateY(-1px);
        }
        .admin-pagination .apg-nav-disabled {
            padding: 0 0.75rem;
            color: #c4c4c4;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
            font-size: 0.8rem;
        }
        /* Ellipsis */
        .admin-pagination .apg-dots {
            color: #9ca3af;
            background: transparent;
            border-color: transparent;
            min-width: 1.5rem;
            cursor: default;
        }
    </style>

    {{-- Left: info --}}
    <p class="admin-pagination-left">
        Showing <span>{{ $paginator->firstItem() }}</span>–<span>{{ $paginator->lastItem() }}</span>
        of <span>{{ $paginator->total() }}</span> results
    </p>

    {{-- Right: page buttons --}}
    <div class="admin-pagination-right">

        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <span class="apg-btn apg-nav-disabled">&#8592; Prev</span>
        @else
            <a class="apg-nav" href="{{ $paginator->previousPageUrl() }}" rel="prev">&#8592; Prev</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="apg-btn apg-dots">···</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="apg-btn apg-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="apg-page" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <a class="apg-nav" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &#8594;</a>
        @else
            <span class="apg-btn apg-nav-disabled">Next &#8594;</span>
        @endif

    </div>
</nav>
@endif
