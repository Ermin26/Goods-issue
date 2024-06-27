@if ($paginator->hasPages())
<div class="pagination-wrapper">
  <!-- Pagination Information -->
  <div class="pagination-info mb-2 text-center text-info">
    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} records
  </div>

  <!-- Pagination Links -->
  <nav aria-label="Page navigation">
    <ul class="pagination mb-0">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
        <span class="page-link" aria-hidden="true">&lsaquo;&lsaquo;</span>
      </li>
      @else
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;&lsaquo;</a>
      </li>
      @endif

      {{-- First Page Link --}}
      @if($paginator->currentPage() == 1)
        <li class="page-item active">
          <span class="page-link" href="{{ $paginator->url(1) }}">1</span>
        </li>
      @else
        <li>
          <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
        </li>
        @endif

      {{-- Pagination Elements --}}
      @if ($paginator->currentPage() > 3)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif

      @for ($i = max(2, $paginator->currentPage() - 1); $i <= min($paginator->lastPage() - 1, $paginator->currentPage() + 1); $i++)
      @if($i == $paginator->currentPage())
        <li class="page-item active">
          <span class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</span>
        </li>
        @else
        <li>
          <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
        </li>
        @endif
      @endfor

      @if ($paginator->currentPage() < $paginator->lastPage() - 2)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif

      {{-- Last Page Link --}}
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
      </li>

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;&rsaquo;</a>
      </li>
      @else
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
        <span class="page-link" aria-hidden="true">&rsaquo;&rsaquo;</span>
      </li>
      @endif
    </ul>
  </nav>
</div>
@endif
