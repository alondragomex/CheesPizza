@extends('layouts.app')

@section('title', 'Menú de Pizzería Cheese Pizza 🍕')

@section('content')
    <style>
        .order-type-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .order-type-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0.85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .order-type-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(245, 158, 11, 0.4);
        }
        .order-type-card.active {
            background: rgba(220, 38, 38, 0.12);
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(220, 38, 38, 0.25);
        }
        .order-type-card.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
        }
        .order-type-icon {
            width: 24px;
            height: 24px;
            margin-bottom: 0.4rem;
            color: var(--text-muted);
            transition: color 0.3s ease;
        }
        .order-type-card.active .order-type-icon {
            color: var(--accent);
        }
        .order-type-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-main);
            display: block;
        }
        .order-type-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
            margin-top: 0.1rem;
        }
        
        .progress-bar-fill {
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.4s ease;
        }
    </style>


    <!-- Hero Banner -->
    <div class="hero">
        <h1>Sabores que <em>Enamoran</em></h1>
        <p>Elige tu especialidad favorita de pizzas artesanales hechas en horno de piedra o acompáñalas con nuestros crujientes adicionales.</p>
    </div>

    <!-- Main Grid -->
    <div class="menu-wrapper">
        
        <!-- Left Side: Products List -->
        <div>
            
            <!-- Category: Pizzas -->
            <div id="pizzas-section">
                <h2 class="section-title">Nuestras Pizzas 🍕</h2>
                <div class="products-grid">
                    @forelse($pizzas as $pizza)
                        <div class="product-card">
                            <div class="product-image-container">
                                <span class="product-badge">Pizza</span>
                                @if($pizza->image_url)
                                    <img src="{{ $pizza->image_url }}" alt="{{ $pizza->name }}" class="product-image">
                                @else
                                    <div class="product-image" style="background:#221515; display:flex; align-items:center; justify-content:center;">🍕</div>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $pizza->name }}</h3>
                                <p class="product-desc">{{ $pizza->description }}</p>
                                <div class="product-footer">
                                    <span class="product-price">${{ number_format($pizza->price, 2) }}</span>
                                    <button class="btn-add-cart" onclick="addToCart({{ $pizza->id }}, '{{ $pizza->name }}', {{ $pizza->price }}, 'pizza')">
                                        <i data-lucide="shopping-cart" style="width:16px; height:16px;"></i>
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color:var(--text-muted);">No hay pizzas disponibles en este momento.</p>
                    @endforelse
                </div>
            </div>

            <!-- Category: Additionals -->
            <div id="additionals-section" style="margin-top: 2rem;">
                <h2 class="section-title">Adicionales & Snacks 🍟</h2>
                <div class="products-grid">
                    @forelse($additionals as $additional)
                        <div class="product-card">
                            <div class="product-image-container">
                                <span class="product-badge additional">Adicional</span>
                                @if($additional->image_url)
                                    <img src="{{ $additional->image_url }}" alt="{{ $additional->name }}" class="product-image">
                                @else
                                    <div class="product-image" style="background:#221515; display:flex; align-items:center; justify-content:center;">🍟</div>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $additional->name }}</h3>
                                <p class="product-desc">{{ $additional->description }}</p>
                                <div class="product-footer">
                                    <span class="product-price">${{ number_format($additional->price, 2) }}</span>
                                    <button class="btn-add-cart" onclick="addToCart({{ $additional->id }}, '{{ $additional->name }}', {{ $additional->price }}, 'additional')">
                                        <i data-lucide="shopping-cart" style="width:16px; height:16px;"></i>
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color:var(--text-muted);">No hay adicionales disponibles en este momento.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Side: Sticky Shopping Cart -->
        <aside class="cart-panel">
            <h3 class="cart-title">
                Tu Pedido 
                <span class="badge" id="cart-count-badge">0</span>
            </h3>

            @if(session()->has('customer_phone'))
                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 8px; padding: 0.6rem 0.85rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                    <div>
                        <span style="color: var(--text-muted);">Cliente:</span>
                        <strong style="color: var(--accent);">{{ session('customer_name') }}</strong><br>
                        <span style="font-size:0.75rem; color: var(--text-muted);">📞 {{ session('customer_phone') }}</span>
                    </div>
                    <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-remove-item" title="Cerrar sesión de cliente" style="padding: 0.25rem; cursor: pointer;">
                            <i data-lucide="log-out" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Cart Items Container -->
            <div class="cart-items" id="cart-items-container">
                <!-- Cart items will be loaded dynamically by Javascript -->
                <div class="cart-empty">
                    <i data-lucide="shopping-bag" style="width:48px; height:48px; margin-bottom:0.5rem; opacity:0.5;"></i>
                    <p>Tu carrito está vacío</p>
                </div>
            </div>

            <!-- Delivery Free Progress Bar Container -->
            <div id="delivery-promo-container" style="display: none; margin-bottom: 1.25rem; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 12px; padding: 0.85rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.85rem;">
                    <span id="delivery-promo-text" style="font-weight: 500; color: var(--text-muted);">Cargando...</span>
                    <span id="delivery-promo-icon" style="color: var(--accent);">🛵</span>
                </div>
                <div class="progress-bar-bg" style="width: 100%; height: 6px; background: rgba(255, 255, 255, 0.08); border-radius: 4px; overflow: hidden;">
                    <div id="delivery-promo-progress" class="progress-bar-fill" style="width: 0%; height: 100%; background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%); border-radius: 4px;"></div>
                </div>
            </div>

            <!-- Cart Total Summary -->
            <div class="cart-summary">
                <div class="cart-summary-row" style="display:flex; justify-content:space-between; font-size:0.95rem; color:var(--text-muted); margin-bottom:0.4rem;">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal-amount">$0.00</span>
                </div>
                
                <div id="cart-row-discount-2x1" class="cart-summary-row" style="display:none; justify-content:space-between; font-size:0.95rem; color:#34d399; margin-bottom:0.4rem; font-weight: 500;">
                    <span>Descuento 2x1 Pizzas 🎁:</span>
                    <span id="cart-discount-2x1-amount">-$0.00</span>
                </div>

                <div id="cart-row-delivery-fee" class="cart-summary-row" style="display:flex; justify-content:space-between; font-size:0.95rem; color:var(--text-muted); margin-bottom:0.8rem;">
                    <span>Costo de Envío:</span>
                    <span id="cart-delivery-fee-amount">$0.00</span>
                </div>

                <div class="cart-total-row" style="border-top:1px solid var(--border-color); padding-top:0.75rem; display:flex; justify-content:space-between; font-size:1.35rem; font-weight:700;">
                    <span>Total:</span>
                    <span id="cart-total-amount">$0.00</span>
                </div>
            </div>

            <!-- Customer Checkout Form -->
            <div id="checkout-form-container" style="display: none;">
                <form action="{{ route('order.store') }}" method="POST" class="order-form">
                    @csrf
                    <!-- Hidden field containing the serialized cart JSON -->
                    <input type="hidden" name="cart_data" id="cart_data_field">
                    <input type="hidden" name="order_type" id="order_type_field" value="delivery">
                    <input type="hidden" name="customer_address" id="customer_address">

                    <div class="form-group">
                        <label style="margin-bottom: 0.5rem; display: block;">Tipo de Pedido</label>
                        <div class="order-type-selector">
                            <div class="order-type-card active" id="type-delivery-card" onclick="setOrderType('delivery')">
                                <i data-lucide="truck" class="order-type-icon"></i>
                                <span class="order-type-title">Domicilio</span>
                                <span class="order-type-subtitle">Envío regular</span>
                            </div>
                            <div class="order-type-card" id="type-pickup-card" onclick="setOrderType('pickup')">
                                <i data-lucide="store" class="order-type-icon"></i>
                                <span class="order-type-title">Recoger</span>
                                <span class="order-type-subtitle">En sucursal</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer_name">Nombre Completo</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Ej. Juan Pérez" required readonly value="{{ session('customer_name') }}">
                    </div>

                    <div class="form-group">
                        <label for="customer_phone">Teléfono de Contacto</label>
                        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" placeholder="Ej. 5512345678" required readonly value="{{ session('customer_phone') }}">
                    </div>

                    <!-- Sucursal Info Box -->
                    <div id="sucursal-info-container" style="display: none; margin-bottom: 1rem; background: rgba(245, 158, 11, 0.05); border: 1px dashed var(--border-accent); border-radius: 12px; padding: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <i data-lucide="map-pin" style="color: var(--accent); width: 20px; height: 20px; flex-shrink: 0; margin-top: 0.15rem;"></i>
                            <div>
                                <h4 style="font-family: var(--font-serif); color: var(--accent); font-size: 1rem; margin-bottom: 0.25rem;">Cheese Pizza Sucursal Centro</h4>
                                <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin: 0;">
                                    Av. Principal #123, Col. Centro, CP 01000.<br>
                                    <strong>Horario:</strong> Lunes a Domingo de 12:00 PM a 10:00 PM.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="address-group" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <div class="form-group">
                                <label for="address_calle">Calle</label>
                                <input type="text" id="address_calle" class="form-control" placeholder="Ej. Av. Reforma" oninput="syncAddressFields()" required>
                            </div>
                            <div class="form-group">
                                <label for="address_colonia">Colonia</label>
                                <input type="text" id="address_colonia" class="form-control" placeholder="Ej. Centro" oninput="syncAddressFields()" required>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <div class="form-group">
                                <label for="address_num_ext">No. Exterior</label>
                                <input type="text" id="address_num_ext" class="form-control" placeholder="Ej. 123" pattern="[0-9]{1,6}" maxlength="6" inputmode="numeric" title="El número exterior debe tener máximo 6 dígitos numéricos" oninput="this.value = this.value.replace(/[^0-9]/g, ''); syncAddressFields();" required>
                            </div>
                            <div class="form-group">
                                <label for="address_num_int">No. Interior <span style="font-size:0.75rem; opacity:0.5;">(Opcional)</span></label>
                                <input type="text" id="address_num_int" class="form-control" placeholder="Ej. Apt 4B" oninput="syncAddressFields()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_reference">Referencias / Indicaciones <span style="font-size:0.75rem; opacity:0.5;">(Opcional)</span></label>
                            <input type="text" id="address_reference" class="form-control" placeholder="Ej. Portón negro, entre calle X y Y" oninput="syncAddressFields()">
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout">
                        Enviar Pedido AHORA 🍕🚀
                    </button>
                </form>
            </div>
        </aside>

    @if(!session()->has('customer_phone'))
        <!-- Floating Backdrop Modal for Customer Registration -->
        <div id="customer-register-backdrop" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(10, 6, 6, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">
            <div class="product-card" style="width: 100%; max-width: 420px; padding: 2.25rem; background: rgba(25, 15, 15, 0.95); border: 1px solid var(--border-accent); box-shadow: 0 20px 50px rgba(0,0,0,0.8), 0 0 25px rgba(245, 158, 11, 0.1);">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🍕</div>
                    <h3 style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--text-main); margin-bottom: 0.5rem;">¡Bienvenido a Cheese Pizza!</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.4;">Para poder armar tu carrito de compras y realizar tu pedido, por favor regístrate con tu nombre y teléfono.</p>
                </div>
                
                <form action="{{ route('customer.register') }}" method="POST" class="order-form">
                    @csrf
                    <div class="form-group">
                        <label for="reg_customer_name">Nombre Completo</label>
                        <input type="text" name="customer_name" id="reg_customer_name" class="form-control" placeholder="Ej. Juan Pérez" required value="{{ old('customer_name') }}">
                    </div>

                    <div class="form-group" style="margin-top: 1rem;">
                        <label for="reg_customer_phone">Teléfono de Contacto <span style="font-size:0.75rem; opacity:0.6;">(10 dígitos)</span></label>
                        <input type="tel" name="customer_phone" id="reg_customer_phone" class="form-control" placeholder="Ej. 5512345678" pattern="[0-9]{10}" maxlength="10" inputmode="numeric" title="El número de teléfono debe tener exactamente 10 dígitos numéricos" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required value="{{ old('customer_phone') }}">
                    </div>

                    <button type="submit" class="btn-checkout" style="width: 100%; margin-top: 1.5rem; background: var(--accent); color: var(--bg-dark); font-weight: 800; font-size: 1.05rem;">
                        Registrarme y Ver Menú 🍕🚀
                    </button>
                </form>
            </div>
        </div>
    @endif
    </div>

@endsection

@section('scripts')
<script>
    // Shopping Cart State
    let cart = [];
    let orderType = 'delivery'; // Default order type

    // Load cart from localStorage if exists
    if (localStorage.getItem('cheesepizza_cart')) {
        try {
            cart = JSON.parse(localStorage.getItem('cheesepizza_cart'));
            // Ensure all items have a category, default to 'pizza' if not present
            cart.forEach(item => {
                if (!item.category) {
                    item.category = 'pizza';
                }
            });
        } catch (e) {
            cart = [];
        }
    }

    // Initialize/Render Cart
    renderCart();

    // Set Order Type
    function setOrderType(type) {
        orderType = type;
        document.getElementById('order_type_field').value = type;

        const deliveryCard = document.getElementById('type-delivery-card');
        const pickupCard = document.getElementById('type-pickup-card');
        const addressGroup = document.getElementById('address-group');
        const sucursalInfo = document.getElementById('sucursal-info-container');

        const calleField = document.getElementById('address_calle');
        const coloniaField = document.getElementById('address_colonia');
        const extField = document.getElementById('address_num_ext');

        if (type === 'delivery') {
            deliveryCard.classList.add('active');
            pickupCard.classList.remove('active');
            addressGroup.style.display = 'flex';
            calleField.setAttribute('required', 'required');
            coloniaField.setAttribute('required', 'required');
            extField.setAttribute('required', 'required');
            sucursalInfo.style.display = 'none';
        } else {
            pickupCard.classList.add('active');
            deliveryCard.classList.remove('active');
            addressGroup.style.display = 'none';
            calleField.removeAttribute('required');
            coloniaField.removeAttribute('required');
            extField.removeAttribute('required');
            sucursalInfo.style.display = 'block';
        }

        syncAddressFields();
        renderCart();
    }

    // Synchronize Address Fields
    function syncAddressFields() {
        const calle = document.getElementById('address_calle').value.trim();
        const colonia = document.getElementById('address_colonia').value.trim();
        const numExt = document.getElementById('address_num_ext').value.trim();
        const numInt = document.getElementById('address_num_int').value.trim();
        const reference = document.getElementById('address_reference').value.trim();

        if (orderType === 'delivery') {
            if (calle || colonia || numExt) {
                let addressText = `Calle: ${calle}, Col: ${colonia}, No. Ext: ${numExt}`;
                if (numInt) {
                    addressText += `, No. Int: ${numInt}`;
                }
                if (reference) {
                    addressText += `, Ref: ${reference}`;
                }
                document.getElementById('customer_address').value = addressText;
            } else {
                document.getElementById('customer_address').value = '';
            }
        } else {
            document.getElementById('customer_address').value = 'Retiro en Sucursal (Cheese Pizza Centro)';
        }
    }

    // Add item to cart
    function addToCart(id, name, price, category = 'pizza') {
        const existingItem = cart.find(item => item.id === id);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                category: category,
                quantity: 1
            });
        }

        saveCart();
        renderCart();
        
        // Visual micro-interaction (glowing alert or bounce)
        animateCartBadge();
    }

    // Update quantity of an item
    function updateQuantity(id, change) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                removeFromCart(id);
                return;
            }
        }
        saveCart();
        renderCart();
    }

    // Remove item completely from cart
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
        renderCart();
    }

    // Save cart state
    function saveCart() {
        localStorage.setItem('cheesepizza_cart', JSON.stringify(cart));
        document.getElementById('cart_data_field').value = JSON.stringify(cart);
    }

    // Render cart elements and calculations
    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const checkoutForm = document.getElementById('checkout-form-container');
        
        // Cart Summary Breakdown Elements
        const subtotalEl = document.getElementById('cart-subtotal-amount');
        const discount2x1Row = document.getElementById('cart-row-discount-2x1');
        const discount2x1El = document.getElementById('cart-discount-2x1-amount');
        const deliveryFeeRow = document.getElementById('cart-row-delivery-fee');
        const deliveryFeeEl = document.getElementById('cart-delivery-fee-amount');
        const totalAmountEl = document.getElementById('cart-total-amount');
        const badgeEl = document.getElementById('cart-count-badge');

        // Free Delivery Promo Elements
        const promoContainer = document.getElementById('delivery-promo-container');
        const promoText = document.getElementById('delivery-promo-text');
        const promoProgress = document.getElementById('delivery-promo-progress');

        // Update hidden field value
        document.getElementById('cart_data_field').value = JSON.stringify(cart);

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="cart-empty">
                    <i data-lucide="shopping-bag" style="width:48px; height:48px; margin-bottom:0.5rem; opacity:0.5;"></i>
                    <p>Tu carrito está vacío</p>
                </div>
            `;
            subtotalEl.innerText = "$0.00";
            discount2x1Row.style.display = "none";
            deliveryFeeEl.innerText = "$0.00";
            totalAmountEl.innerText = "$0.00";
            badgeEl.innerText = "0";
            checkoutForm.style.display = "none";
            promoContainer.style.display = "none";
            
            lucide.createIcons();
            return;
        }

        let html = '';
        let subtotal = 0;
        let totalQty = 0;
        let pizzaPrices = [];

        cart.forEach(item => {
            const itemSubtotal = item.price * item.quantity;
            subtotal += itemSubtotal;
            totalQty += item.quantity;

            if (item.category === 'pizza') {
                for (let i = 0; i < item.quantity; i++) {
                    pizzaPrices.push(item.price);
                }
            }

            html += `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">$${item.price.toFixed(2)} c/u</div>
                    </div>
                    <div class="cart-item-controls">
                        <button class="btn-qty" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span class="cart-item-qty">${item.quantity}</span>
                        <button class="btn-qty" onclick="updateQuantity(${item.id}, 1)">+</button>
                        <button class="btn-remove-item" onclick="removeFromCart(${item.id})">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        // 2x1 Pizza Promotion calculation
        pizzaPrices.sort((a, b) => b - a); // Sort descending
        let discount2x1 = 0;
        for (let i = 1; i < pizzaPrices.length; i += 2) {
            discount2x1 += pizzaPrices[i];
        }

        let after2x1 = subtotal - discount2x1;

        // Delivery fee calculation
        let deliveryFee = 0.00;
        if (orderType === 'delivery') {
            deliveryFeeRow.style.display = 'flex';
            if (after2x1 >= 300) {
                deliveryFee = 0.00;
                deliveryFeeEl.innerHTML = '<span style="color:#34d399; font-weight:600;">GRATIS</span>';
            } else {
                deliveryFee = 45.00;
                deliveryFeeEl.innerText = `$${deliveryFee.toFixed(2)}`;
            }

            // Free Delivery Promotion Progress Bar
            promoContainer.style.display = 'block';
            if (after2x1 >= 300) {
                promoText.innerHTML = '¡Felicidades! Tienes <strong>Envío GRATIS</strong> 🛵🎉';
                promoProgress.style.width = '100%';
                promoProgress.style.backgroundColor = '#10b981';
            } else {
                const missing = 300 - after2x1;
                promoText.innerHTML = `Faltan <strong>$${missing.toFixed(2)}</strong> para <strong>Envío GRATIS</strong> 🛵`;
                const percentage = (after2x1 / 300) * 100;
                promoProgress.style.width = `${percentage}%`;
                promoProgress.style.backgroundColor = ''; // standard theme gradient
            }
        } else {
            // Pickup
            deliveryFee = 0.00;
            deliveryFeeEl.innerHTML = '<span style="color:#f59e0b; font-weight:600;">$0.00 (Retiro)</span>';
            promoContainer.style.display = 'none';
        }

        const total = subtotal - discount2x1 + deliveryFee;

        // Render summary rows
        subtotalEl.innerText = `$${subtotal.toFixed(2)}`;
        
        if (discount2x1 > 0) {
            discount2x1Row.style.display = 'flex';
            discount2x1El.innerText = `-$${discount2x1.toFixed(2)}`;
        } else {
            discount2x1Row.style.display = 'none';
        }

        container.innerHTML = html;
        totalAmountEl.innerText = `$${total.toFixed(2)}`;
        badgeEl.innerText = totalQty;
        checkoutForm.style.display = "block";

        lucide.createIcons();
    }

    function animateCartBadge() {
        const badge = document.getElementById('cart-count-badge');
        badge.style.transform = 'scale(1.3)';
        badge.style.transition = 'transform 0.15s ease-in-out';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 150);
    }

    // Clear cart on successful order submission
    @if(session('success'))
        localStorage.removeItem('cheesepizza_cart');
        cart = [];
        renderCart();
    @endif
</script>
@endsection
