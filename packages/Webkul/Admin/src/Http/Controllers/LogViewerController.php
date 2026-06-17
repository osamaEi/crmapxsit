<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;

class LogViewerController extends Controller
{
    private string $logFile;

    public function __construct()
    {
        $this->logFile = storage_path('logs/laravel.log');
    }

    public function index(Request $request)
    {
        $lines = $this->readLog();
        $entries = $this->parseEntries($lines);
        $filter = $request->query('level', 'all');
        $search = $request->query('search', '');

        if ($filter !== 'all') {
            $entries = array_filter($entries, fn ($e) => strtolower($e['level']) === strtolower($filter));
        }

        if ($search) {
            $entries = array_filter($entries, fn ($e) => stripos($e['raw'], $search) !== false);
        }

        $entries = array_values(array_slice(array_reverse($entries), 0, 200));

        return view('admin::logs.index', compact('entries', 'filter', 'search'));
    }

    public function clear()
    {
        if (file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }

        return redirect()->route('admin.logs.index')->with('success', 'Log file cleared.');
    }

    private function readLog(): array
    {
        if (! file_exists($this->logFile)) {
            return [];
        }

        return file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    }

    private function parseEntries(array $lines): array
    {
        $entries = [];
        $current = null;

        foreach ($lines as $line) {
            // Match log header: [2024-01-01 12:00:00] local.ERROR: ...
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.*)$/', $line, $m)) {
                if ($current) {
                    $entries[] = $current;
                }

                $current = [
                    'datetime' => $m[1],
                    'level' => strtoupper($m[2]),
                    'message' => $m[3],
                    'stack' => '',
                    'raw' => $line,
                ];
            } elseif ($current) {
                $current['stack'] .= "\n".$line;
                $current['raw'] .= "\n".$line;
            }
        }

        if ($current) {
            $entries[] = $current;
        }

        return $entries;
    }
}
