@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col items-center gap-3">
        <div class="flex items-center justify-center">
            <ul class="inline-flex items-center gap-1">
                
                {{-- Tombol Sebelumnya --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-800 text-zinc-600 border border-zinc-700 cursor-not-allowed">
                            <i class="fa-solid fa-angle-left"></i>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" 
                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-700 text-zinc-300 border border-zinc-600 hover:bg-zinc-600 hover:text-white transition-all duration-200">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Nomor Halaman --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-800 text-zinc-500 border border-zinc-700">
                                {{ $element }}
                            </span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-600 text-white border border-blue-500 font-semibold shadow-lg">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" 
                                       class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-700 text-zinc-300 border border-zinc-600 hover:bg-zinc-600 hover:text-white transition-all duration-200">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Selanjutnya --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" 
                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-700 text-zinc-300 border border-zinc-600 hover:bg-zinc-600 hover:text-white transition-all duration-200">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                @else
                    <li>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-800 text-zinc-600 border border-zinc-700 cursor-not-allowed">
                            <i class="fa-solid fa-angle-right"></i>
                        </span>
                    </li>
                @endif

            </ul>
        </div>
        
        {{-- Info jumlah data --}}
        @if ($paginator->hasPages())
            <div class="w-full text-center">
                <p class="text-sm text-zinc-400">
                    Showing 
                    <span class="font-medium text-zinc-300">{{ $paginator->firstItem() }}</span>
                    to 
                    <span class="font-medium text-zinc-300">{{ $paginator->lastItem() }}</span>
                    of 
                    <span class="font-medium text-zinc-300">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>
        @endif
    </nav>
@endif