<?php

namespace App\Http\Middleware;

use App\Services\SessionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateSessionActivity
{
    public function __construct(
        private readonly SessionService $sessionService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->bearerToken();

        if ($plainToken !== null && $request->user() !== null) {
            $session = $this->sessionService->findByToken($plainToken);

            if ($session === null || ! $this->sessionService->validateActive($session)) {
                if ($session !== null) {
                    $this->sessionService->terminate($session, $request->user(), 'session_timeout');
                }

                $request->user()->tokens()->delete();

                return response()->json([
                    'message' => 'Session expired due to inactivity.',
                ], 401);
            }

            $this->sessionService->touch($session);
            $request->attributes->set('user_session_id', $session->id);
        }

        return $next($request);
    }
}
