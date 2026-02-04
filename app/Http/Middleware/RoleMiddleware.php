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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $userRole = $request->user()->role;

        // Se o argumento for 'admin', verifica qualquer role administrativa através do enum
        if (in_array('admin', $roles)) {
            if ($userRole->isAdmin()) {
                return $next($request);
            }
        }

        // Caso contrário, verifica se a role do usuário está entre os argumentos permitidos
        if (in_array($userRole->value, $roles)) {
            return $next($request);
        }

        abort(403, 'Acesso não autorizado para esta funcionalidade.');
    }
}
