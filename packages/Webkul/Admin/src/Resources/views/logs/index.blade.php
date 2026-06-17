<x-admin::layouts>
    <x-slot:title>Application Logs</x-slot>

    @push('styles')
    <style>
        .log-badge { display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; letter-spacing:.4px; }
        .log-ERROR   { background:#fee2e2; color:#dc2626; }
        .log-WARNING { background:#fef3c7; color:#d97706; }
        .log-INFO    { background:#dbeafe; color:#2563eb; }
        .log-DEBUG   { background:#f3f4f6; color:#6b7280; }
        .log-CRITICAL{ background:#fce7f3; color:#db2777; }
        .log-NOTICE  { background:#ecfdf5; color:#059669; }

        .log-row { border-bottom: 1px solid #f1f5f9; }
        .log-row:hover { background: #fafafa; }
        .log-stack { font-family: monospace; font-size: 12px; white-space: pre-wrap; word-break: break-all;
                     color: #6b7280; max-height: 300px; overflow-y: auto;
                     background: #f8fafc; padding: 10px 12px; border-radius: 6px; margin-top: 6px; }

        .dark .log-row:hover { background: #1f2937; }
        .dark .log-stack { background: #111827; color: #9ca3af; }
        .dark .log-ERROR   { background:#450a0a; color:#fca5a5; }
        .dark .log-WARNING { background:#451a03; color:#fcd34d; }
        .dark .log-INFO    { background:#1e3a5f; color:#93c5fd; }
        .dark .log-DEBUG   { background:#1f2937; color:#9ca3af; }
        .dark .log-CRITICAL{ background:#4a044e; color:#f0abfc; }
        .dark .log-NOTICE  { background:#064e3b; color:#6ee7b7; }
    </style>
    @endpush

    <div class="flex flex-col gap-4">

        <!-- Header -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <div class="text-xl font-bold dark:text-white">Application Logs</div>
                <p class="text-xs text-gray-400">Last 200 entries · {{ storage_path('logs/laravel.log') }}</p>
            </div>
            <form method="POST" action="{{ route('admin.logs.clear') }}" onsubmit="return confirm('Clear all logs?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="secondary-button text-red-500 border-red-200 hover:bg-red-50">
                    Clear Logs
                </button>
            </form>
        </div>

        <!-- Flash success -->
        @if (session('success'))
            <div class="flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-wrap items-center gap-3 w-full">

                <!-- Level filter -->
                <div class="flex gap-1 flex-wrap">
                    @foreach(['all', 'ERROR', 'WARNING', 'INFO', 'DEBUG', 'CRITICAL'] as $lvl)
                        <a
                            href="{{ route('admin.logs.index', ['level' => $lvl, 'search' => $search]) }}"
                            class="px-3 py-1 rounded-full text-xs font-semibold border transition-all
                                {{ $filter === $lvl
                                    ? 'bg-brandColor text-white border-brandColor'
                                    : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' }}"
                        >
                            {{ $lvl === 'all' ? 'All' : $lvl }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <div class="flex flex-1 min-w-[200px] items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search in logs..."
                        class="flex-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm outline-none focus:border-brandColor dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                    <input type="hidden" name="level" value="{{ $filter }}" />
                    <button type="submit" class="primary-button py-1.5 px-3 text-xs">Search</button>
                    @if($search)
                        <a href="{{ route('admin.logs.index', ['level' => $filter]) }}" class="text-xs text-gray-400 hover:text-gray-600">✕ Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Log entries -->
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">

            @if (count($entries) === 0)
                <div class="flex flex-col items-center justify-center gap-3 py-16 text-gray-400">
                    <span class="text-5xl">📋</span>
                    <p class="text-sm font-medium">No log entries found</p>
                    @if ($search || $filter !== 'all')
                        <a href="{{ route('admin.logs.index') }}" class="text-xs text-brandColor hover:underline">Clear filters</a>
                    @endif
                </div>
            @else
                <div class="px-1">
                    <div class="flex items-center justify-between px-3 py-2 text-xs text-gray-400 border-b border-gray-100 dark:border-gray-800">
                        <span>Showing {{ count($entries) }} entries (newest first)</span>
                    </div>

                    @foreach ($entries as $i => $entry)
                        <div class="log-row px-3 py-3">
                            <div class="flex items-start gap-3">
                                <!-- Level badge -->
                                <span class="log-badge log-{{ $entry['level'] }} mt-0.5 shrink-0">
                                    {{ $entry['level'] }}
                                </span>

                                <div class="flex-1 min-w-0">
                                    <!-- Datetime + message -->
                                    <div class="flex flex-wrap items-baseline gap-2">
                                        <span class="text-xs text-gray-400 shrink-0">{{ $entry['datetime'] }}</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200 break-all">
                                            {{ Str::limit($entry['message'], 300) }}
                                        </span>
                                    </div>

                                    <!-- Stack trace toggle -->
                                    @if (trim($entry['stack']))
                                        <details class="mt-2">
                                            <summary class="cursor-pointer text-xs text-brandColor hover:underline select-none">
                                                Show stack trace
                                            </summary>
                                            <div class="log-stack mt-2">{{ trim($entry['stack']) }}</div>
                                        </details>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-admin::layouts>
