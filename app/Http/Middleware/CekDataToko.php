<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CekDataToko
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah data pada tabel toko kosong
        if (Toko::count() === 0) {
            return $next($request);
        }
        if(Auth()->check()) {
            return back();
        } else {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Toko Berhasil Diaktivasi',
                'message' => "Silahkan melakukan validasi akun admin.",
            ]);
            return redirect()->route('login');
        }
        return $next($request);
    }
}
