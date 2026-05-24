<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $pizzas = Product::where('category', 'pizza')->get();
        $additionals = Product::where('category', 'additional')->get();

        return view('customer.menu', compact('pizzas', 'additionals'));
    }

    public function storeOrder(Request $request)
    {
        // Enforce active customer registration session
        if (!session()->has('customer_phone') || !session()->has('customer_name')) {
            return back()->withErrors(['error' => 'Debes validar tu número de teléfono antes de enviar un pedido.'])->withInput();
        }

        $request->validate([
            'customer_address' => 'nullable|string',
            'order_type' => 'required|in:pickup,delivery',
            'cart_data' => 'required|string',
        ]);

        $customerName = session('customer_name');
        $customerPhone = session('customer_phone');

        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return back()->withErrors(['error' => 'El carrito está vacío. Agrega algunos productos antes de enviar.'])->withInput();
        }

        // Determine address based on order type
        $orderType = $request->order_type;
        $address = $request->customer_address;
        if ($orderType === 'pickup' && empty($address)) {
            $address = 'Retiro en Sucursal (Cheese Pizza Centro)';
        } elseif ($orderType === 'delivery' && empty($address)) {
            return back()->withErrors(['customer_address' => 'La dirección de envío es obligatoria para pedidos a domicilio.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $pizzaPrices = [];
            $itemsData = [];

            foreach ($cart as $cartItem) {
                $product = Product::find($cartItem['id']);
                if (!$product) {
                    throw new \Exception("El producto con ID {$cartItem['id']} no se encuentra disponible.");
                }

                $quantity = intval($cartItem['quantity']);
                if ($quantity <= 0) continue;

                $itemSubtotal = $product->price * $quantity;
                $subtotal += $itemSubtotal;

                if ($product->category === 'pizza') {
                    for ($i = 0; $i < $quantity; $i++) {
                        $pizzaPrices[] = floatval($product->price);
                    }
                }

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];
            }

            if (empty($itemsData)) {
                throw new \Exception("No hay productos válidos en el carrito.");
            }

            // Calculate 2x1 Pizza Promotion
            rsort($pizzaPrices);
            $discount_2x1 = 0;
            for ($i = 1; $i < count($pizzaPrices); $i += 2) {
                $discount_2x1 += $pizzaPrices[$i];
            }

            // Calculate Delivery Fee
            $delivery_fee = 0.00;
            if ($orderType === 'delivery') {
                $after_2x1 = $subtotal - $discount_2x1;
                if ($after_2x1 < 300.00) {
                    $delivery_fee = 45.00;
                }
            }

            $total = $subtotal - $discount_2x1 + $delivery_fee;

            // Create Order
            $order = Order::create([
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_address' => $address,
                'order_type' => $orderType,
                'subtotal' => $subtotal,
                'discount_2x1' => $discount_2x1,
                'delivery_fee' => $delivery_fee,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create Order Items
            foreach ($itemsData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            $successMsg = '¡Tu pedido ha sido recibido con éxito! En breve estará listo. Tu número de pedido es #' . $order->id;
            if ($discount_2x1 > 0) {
                $successMsg .= ' ¡Ahorraste $' . number_format($discount_2x1, 2) . ' con la promoción 2x1! 🍕🎉';
            }

            return redirect()->route('menu')->with('success', $successMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Hubo un problema al procesar tu pedido: ' . $e->getMessage()])->withInput();
        }
    }
}
