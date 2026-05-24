<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10}$/',
        ], [
            'customer_phone.regex' => 'El número de teléfono debe tener exactamente 10 dígitos numéricos.',
        ]);

        // Buscar el cliente por su teléfono, o crearlo si no existe
        $customer = Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            ['name' => $request->customer_name]
        );

        // Guardar datos en la sesión activa del cliente
        session([
            'customer_phone' => $customer->phone,
            'customer_name' => $customer->name,
        ]);

        return redirect()->back()->with('success', '¡Bienvenido(a) a Cheese Pizza, ' . $customer->name . '! Ya puedes armar tu pedido.');
    }

    public function logout()
    {
        session()->forget(['customer_name', 'customer_phone']);
        return redirect()->back()->with('success', 'Has cerrado tu sesión de cliente correctamente.');
    }
}
