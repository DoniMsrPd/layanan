<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class KeycloakMiddleware
{

    /**
     * Validate an Api Key of incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function handle($request, Closure $next)
    {
		try {
            $nip = null;
            $proxyNip = $request->header('X-Proxy-Nip');

            // HANYA configure proxy_key kalau memang di belakang custom proxy!!!

            if (!empty($proxyNip)
                    && config('auth.proxy_key')
                    && config('auth.proxy_key') === $request->header('X-Proxy-Token')) {
                $nip = $proxyNip;
            } else {
                $token = self::decode($request->bearerToken(), config('keycloak.public_key'));
                $nip = $token->nip;
            }

            if ($nip) {
                $user = User::query()
                    ->where('NIP', $nip)
                    ->without(['roles'])
                    ->first();

                if ($user) {
                    Auth::login($user);
                    return $next($request);
                }
            }
        } catch (Exception $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        }

        throw new AccessDeniedHttpException("Unauthorized");
    }

    public static function decode(string $token = null, string $publicKey)
    {

        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($publicKey, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        return $token ? JWT::decode($token, new Key($publicKey, 'RS256')) : null;
    }
}

