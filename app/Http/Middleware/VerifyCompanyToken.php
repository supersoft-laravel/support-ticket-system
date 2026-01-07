<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCompanyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Header se token lo
        $token = $request->header('X-COMPANY-TOKEN');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Company token is missing'
            ], 401);
        }

        // Company verify karo
        $company = Company::where('token', $token)
            ->where('is_active', 'active')
            ->first();

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid company token'
            ], 403);
        }

        $request->merge(['company' => $company]);

        return $next($request);
    }
}
