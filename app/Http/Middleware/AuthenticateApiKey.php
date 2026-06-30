<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessKey = $request->header('X-Access-Key') ?? $request->query('access_key');
        $secretKey = $request->header('X-Secret-Key') ?? $request->query('secret_key');

        if (!$accessKey || !$secretKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Missing Access Key or Secret Key.'
            ], 401);
        }

        // Fetch active credentials
        $credential = DB::table('access_credentials')
            ->where('access_key', $accessKey)
            ->where('is_active', true)
            ->first();

        if (!$credential) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid Access Key.'
            ], 401);
        }

        // Verify Secret Key
        try {
            $decryptedSecret = decrypt($credential->secret_key_encrypted);
            if ($decryptedSecret !== $secretKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Invalid Secret Key.'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid credentials decryption.'
            ], 401);
        }

        // Fetch associated provisioned resource
        $resource = DB::table('provisioned_resources')
            ->where('id', $credential->provisioned_id)
            ->first();

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Resource associated with these credentials was not found.'
            ], 404);
        }

        if ($resource->status === 'terminated') {
            return response()->json([
                'success' => false,
                'message' => 'Resource associated with these credentials has been terminated.'
            ], 400);
        }

        // Log in the user programmatically for the duration of this request
        Auth::loginUsingId($credential->user_id);

        // Store resource & credentials on request attributes for use in controller
        $request->attributes->set('api_credential', $credential);
        $request->attributes->set('api_resource', $resource);

        // Update last_used_at timestamp
        DB::table('access_credentials')
            ->where('id', $credential->id)
            ->update([
                'last_used_at' => now(),
                'updated_at'   => now()
            ]);

        return $next($request);
    }
}
