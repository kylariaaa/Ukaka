@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="kyshop-pagination">
    <style>
        .kyshop-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 1.5rem 0;
            flex-wrap: wrap;
        }
        .kyshop-pagination a,
        .kyshop-pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0 0.625rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s ease;
            border: 1.5px solid transparent;
            cursor: pointer;
            user-select: none;
        }
        /* Inactive page links */
        .kyshop-pagination a.page-link {
            color: #4b5563;
            background: #f9fafb;
            border-color: #e5e7eb;
        }
        .kyshop-pagination a.page-link:hover {
            background: #4A0505;
            color: #fff;
            border-color: #4A0505;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(74,5,5,0.18);
        }
        /* Active page */
        .kyshop-pagination span.page-active {
            background: linear-gradient(135deg, #4A0505 0%, #7c1111 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(74,5,5,0.30);
        }
        /* Disabled (prev/next when unavailable) */
        .kyshop-pagination span.page-disabled {
            color: #d1d5db;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        /* Prev/Next navigation arrows */
        .kyshop-pagination a.page-nav,
        .kyshop-pagination span.page-nav-disabled {
            font-size: 1.1rem;
            padding: 0 0.8rem;
            gap: 0.25rem;
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
        /* Ellipsis */
        .kyshop-pagination span.page-dots {
            color: #9ca3af;
            background: transparent;
            border-color: transparent;
            font-size: 1rem;
            letter-spacing: 0.1em;
        }
        /* Info text */
        .kyshop-pagination-info {
            width: 100%;
            text-align: center;
            font-size: 0.75rem;
            color: #9ca3af;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
    </style>

    {{-- Info --}}
    <p class="kyshop-pagination-info">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} produk
    </p>

    {{-- Previous Page --}}
    @if ($paginator->onFirstPage())
        <span class="page-nav-disabled" aria-disabled="true">← Prev</span>
    @else
        <a class="page-nav" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">← Prev</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span class="page-dots" aria-hidden="true">···</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-active" aria-current="page">{{ $page }}</span>
                @else
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page --}}
    @if ($paginator->hasMorePages())
        <a class="page-nav" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">Next →</a>
    @else
        <span class="page-nav-disabled" aria-disabled="true">Next →</span>
    @endif
</nav>
@endif
