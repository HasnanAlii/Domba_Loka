@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- MOBILE --}}
        <div class="flex gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-300 bg-blue-50 border border-blue-200 cursor-not-allowed rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-md hover:bg-blue-50 hover:text-blue-800">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-md hover:bg-blue-50 hover:text-blue-800">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-300 bg-blue-50 border border-blue-200 cursor-not-allowed rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- DESKTOP --}}
        <div class="hidden sm:flex sm:flex-1 sm:gap-2 sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-black leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-black">{{ $paginator->firstItem() }}</span>
                        {!! __('Ke') !!}
                        <span class="font-bold text-black">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-bold text-black">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse shadow-sm rounded-md">

                    {{-- PREV --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-blue-300 bg-blue-50 border border-blue-200 cursor-not-allowed rounded-l-md">
                                <svg class="w-5 h-5" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.7 5.3a1 1 0 010 1.4L9.4 10l3.3 3.3a1 1 0 01-1.4 1.4l-4-4a1 1 0 010-1.4l4-4a1 1 0 011.4 0z"/>
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           class="inline-flex items-center px-2 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-l-md hover:bg-blue-50 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.7 5.3a1 1 0 010 1.4L9.4 10l3.3 3.3a1 1 0 01-1.4 1.4l-4-4a1 1 0 010-1.4l4-4a1 1 0 011.4 0z"/>
                            </svg>
                        </a>
                    @endif

                    {{-- NUMBERS --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-500 bg-white border border-blue-300 cursor-default">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-blue-600 text-white border border-blue-600 cursor-default">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 hover:bg-blue-50 hover:text-blue-800">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- NEXT --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           class="inline-flex items-center px-2 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-r-md hover:bg-blue-50 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.3 14.7a1 1 0 010-1.4L10.6 10 7.3 6.7a1 1 0 011.4-1.4l4 4a1 1 0 010 1.4l-4 4a1 1 0 01-1.4 0z"/>
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-blue-300 bg-blue-50 border border-blue-200 cursor-not-allowed rounded-r-md">
                                <svg class="w-5 h-5" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.3 14.7a1 1 0 010-1.4L10.6 10 7.3 6.7a1 1 0 011.4-1.4l4 4a1 1 0 010 1.4l-4 4a1 1 0 01-1.4 0z"/>
                                </svg>
                            </span>
                        </span>
                    @endif

                </span>
            </div>

        </div>
    </nav>
@endif