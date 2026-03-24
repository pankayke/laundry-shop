<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RuntimeCheckController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $defaultConnection = (string) config('database.default', 'sqlite');
        $sqlitePath = $defaultConnection === 'sqlite'
            ? (string) config('database.connections.sqlite.database', '')
            : null;

        $probeKey = 'runtime_probe';
        $request->session()->put($probeKey, now()->toIso8601String());
        $request->session()->save();

        $checks = [
            'database' => [
                'connection' => $defaultConnection,
                'sqlite_path' => $sqlitePath,
                'sqlite_exists' => $sqlitePath ? is_file($sqlitePath) : null,
                'sqlite_writable' => $sqlitePath ? is_writable($sqlitePath) : null,
                'can_connect' => $this->canConnectToDatabase(),
                'users_count' => User::count(),
                'sessions_table_exists' => Schema::hasTable('sessions'),
                'sessions_count' => Schema::hasTable('sessions') ? DB::table('sessions')->count() : null,
            ],
            'session' => [
                'driver' => (string) config('session.driver'),
                'cookie' => (string) config('session.cookie'),
                'session_id_present' => $request->session()->getId() !== '',
                'probe_value' => $request->session()->get($probeKey),
            ],
            'auth' => [
                'is_authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'user_role' => Auth::user()?->role,
            ],
        ];

        return response()->json([
            'ok' => true,
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ]);
    }

    private function canConnectToDatabase(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
