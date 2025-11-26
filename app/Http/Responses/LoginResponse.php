<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();
        
        // Si el usuario es admin o staff, redirigir al panel de administración
        if ($user && $user->hasRole(['admin', 'staff'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Para otros usuarios, redirigir al dashboard público
        return redirect()->route('dashboard');
    }
}

