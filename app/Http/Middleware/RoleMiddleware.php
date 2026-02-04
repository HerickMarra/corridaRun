<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $userRole = $request->user()->role;

        // Se a role solicitada for 'admin', verifica se o usuário tem qualquer role administrativa
        if ($role === 'admin') {
            if (!$userRole->isAdmin()) {
                abort(403, 'Acesso não autorizado ao painel administrativo.');
            }
        }
        // Caso contrário, verifica a role exata
        elseif ($userRole->value !== $role) {
            abort(403, 'Acesso não autorizado para esta funcionalidade.');
        }

        return $next($request);
    }
}
