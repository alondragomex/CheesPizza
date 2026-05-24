@extends('layouts.app')

@section('title', 'Dashboard Admin - Pedidos 🍕')

@section('content')
<div class="admin-grid">
    
    <!-- Sidebar de Navegación -->
    <aside class="admin-sidebar">
        <h3 style="font-family: var(--font-serif); margin-bottom: 1.5rem; color: var(--accent);">Panel de Control</h3>
        <ul class="admin-menu">
            <li>
                <a href="#" class="admin-menu-link active" id="tab-pedidos-btn" onclick="switchTab('pedidos')">
                    <i data-lucide="shopping-bag" style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:8px;"></i>
                    Pedidos
                </a>
            </li>
            <li>
                <a href="#" class="admin-menu-link" id="tab-customers-btn" onclick="switchTab('customers')">
                    <i data-lucide="users" style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:8px;"></i>
                    Historial
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="admin-menu-link">
                    <i data-lucide="pizza" style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:8px;"></i>
                    Productos
                </a>
            </li>
            <li>
                <a href="{{ route('menu') }}" class="admin-menu-link" style="border-top: 1px solid var(--border-color); margin-top: 1rem; padding-top: 1rem;">
                    <i data-lucide="arrow-left" style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:8px;"></i>
                    Ir al Menú
                </a>
            </li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" id="admin-logout-form" style="display:none;">
                    @csrf
                </form>
                <a href="#" class="admin-menu-link" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" style="color: var(--primary); font-weight: 600;">
                    <i data-lucide="log-out" style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:8px;"></i>
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </aside>

    <!-- Área de Trabajo Principal -->
    <div style="flex-grow: 1;">
        
        <!-- Vista de Pedidos -->
        <div id="tab-pedidos-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <h2 class="section-title" style="margin-bottom: 0;">Pedidos de la Pizzería 🍕</h2>
                
                <form action="{{ route('admin.corte-caja') }}" method="POST" onsubmit="return confirm('🚨 ¡ATENCIÓN! 🚨\n\n¿Estás seguro de realizar el Corte de Caja?\nEsto eliminará todas las órdenes actuales para iniciar un nuevo turno de trabajo. Las ganancias y conteos se restablecerán a $0.00.\n\n*Los clientes registrados (CRM) no se verán afectados.')" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-checkout" style="background: var(--primary); color: white; padding: 0.6rem 1.25rem; font-size: 0.85rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer; border: none; transition: background 0.2s;" onmouseover="this.style.background='var(--primary-hover)'" onmouseout="this.style.background='var(--primary)'">
                        <i data-lucide="circle-slash" style="width: 16px; height: 16px;"></i>
                        Realizar Corte de Caja
                    </button>
                </form>
            </div>

        <!-- Fila de Métricas del Negocio -->
        <div class="metrics-row">
            <div class="metric-card earnings">
                <div class="metric-label">Ventas Entregadas</div>
                <div class="metric-value success">${{ number_format($totalEarnings, 2) }}</div>
            </div>
            <div class="metric-card pending">
                <div class="metric-label">Pedidos Pendientes</div>
                <div class="metric-value highlight">{{ $pendingCount }}</div>
            </div>
            <div class="metric-card" style="position: relative;">
                <div class="metric-label">En Cocina</div>
                <div class="metric-value" style="color: #60a5fa;">{{ $preparingCount }}</div>
            </div>
            <div class="metric-card" style="position: relative;">
                <div class="metric-label">Total Pedidos</div>
                <div class="metric-value" style="color: var(--text-main);">{{ count($orders) }}</div>
            </div>
        </div>

        <!-- Filtro por Estado (Semáforo / Interactivo) -->
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
            <button type="button" class="btn-action-text active" id="filter-all-btn" onclick="filterOrders('all')" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 6px; background: rgba(220, 38, 38, 0.1); color: var(--primary);">Todos</button>
            <button type="button" class="btn-action-text" id="filter-pending-btn" onclick="filterOrders('pending')" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 6px; border-left: 3px solid var(--accent); color: var(--text-main);">Pendientes</button>
            <button type="button" class="btn-action-text" id="filter-preparing-btn" onclick="filterOrders('preparing')" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 6px; border-left: 3px solid #60a5fa; color: var(--text-main);">Preparando</button>
            <button type="button" class="btn-action-text" id="filter-delivered-btn" onclick="filterOrders('delivered')" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 6px; border-left: 3px solid #10b981; color: var(--text-main);">Entregados</button>
        </div>

        <!-- Tabla de Pedidos -->
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Productos Solicitados</th>
                        <th>Total</th>
                        <th>Estado Actual</th>
                        <th>Acción rápida</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <strong>{{ $order->customer_name }}</strong>
                                    @if(($order->order_type ?? 'delivery') == 'pickup')
                                        <span style="background: rgba(245, 158, 11, 0.15); color: var(--accent); border: 1px solid rgba(245, 158, 11, 0.3); font-size: 0.72rem; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 600;">
                                            🏪 Recoger
                                        </span>
                                    @else
                                        <span style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); font-size: 0.72rem; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 600;">
                                            🛵 Domicilio
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size:0.85rem; color:var(--text-muted); margin-top: 0.2rem;">
                                    📞 {{ $order->customer_phone }}
                                </div>
                                <div style="font-size:0.85rem; color:var(--text-muted); margin-top: 0.3rem; line-height: 1.4; word-break: break-word;">
                                    📍 {{ $order->customer_address }}
                                </div>
                            </td>
                            <td>
                                <ul class="order-items-list">
                                    @foreach($order->items as $item)
                                        <li>
                                            {{ $item->quantity }}x 
                                            <strong>{{ $item->product ? $item->product->name : 'Producto Eliminado' }}</strong> 
                                            (${{ number_format($item->price, 2) }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="color:var(--accent); font-weight:700; font-size:1.1rem;">
                                        ${{ number_format($order->total, 2) }}
                                    </span>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; line-height: 1.3;">
                                        <div>Sub: ${{ number_format($order->subtotal ?? $order->total, 2) }}</div>
                                        @if(($order->discount_2x1 ?? 0) > 0)
                                            <div style="color: #34d399;">2x1: -${{ number_format($order->discount_2x1, 2) }}</div>
                                        @endif
                                        @if(($order->order_type ?? 'delivery') == 'delivery')
                                            <div>Envío: ${{ number_format($order->delivery_fee ?? 0.00, 2) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $order->status }}">
                                    @if($order->status == 'pending')
                                        Pendiente
                                    @elseif($order->status == 'preparing')
                                        Preparando
                                    @else
                                        Entregado
                                    @endif
                                </span>
                            </td>
                             <td>
                                 @if($order->status == 'pending')
                                     <div style="display: flex; gap: 0.4rem; align-items: center;">
                                         <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="margin:0;">
                                             @csrf
                                             @method('PATCH')
                                             <input type="hidden" name="status" value="preparing">
                                             <button type="submit" class="btn-action-text" style="background: var(--accent); border-color: var(--accent); color: var(--bg-dark); padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                 <i data-lucide="cooking-pot" style="width: 14px; height: 14px;"></i> Cocinar
                                             </button>
                                         </form>
                                         <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar el pedido #{{ $order->id }}?')" style="margin:0;">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn-action-text" style="background: rgba(220, 38, 38, 0.15); border-color: var(--primary); color: #f87171; padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem; cursor: pointer;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='rgba(220, 38, 38, 0.15)'; this.style.color='#f87171'">
                                                 <i data-lucide="x" style="width: 14px; height: 14px;"></i> Cancelar
                                             </button>
                                         </form>
                                     </div>
                                 @elseif($order->status == 'preparing')
                                     <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="margin:0;">
                                         @csrf
                                         @method('PATCH')
                                         <input type="hidden" name="status" value="delivered">
                                         <button type="submit" class="btn-action-text" style="background: #10b981; border-color: #10b981; color: white; padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                                             <i data-lucide="truck" style="width: 14px; height: 14px;"></i> Entregar
                                         </button>
                                     </form>
                                 @else
                                     <span style="color: #34d399; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.3rem; background: rgba(16, 185, 129, 0.08); padding: 0.35rem 0.75rem; border-radius: 6px; border: 1px solid rgba(16, 185, 129, 0.15);">
                                         <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i> Entregado
                                     </span>
                                 @endif
                             </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:4rem 0; color:var(--text-muted);">
                                <i data-lucide="package-open" style="width:48px; height:48px; margin-bottom:0.5rem; opacity:0.5; display:block; margin-left:auto; margin-right:auto;"></i>
                                <p>No hay pedidos registrados en el sistema todavía.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> <!-- Fin de table-wrapper -->
    </div> <!-- Fin de tab-pedidos-panel -->

        <!-- Vista de Clientes CRM -->
        <div id="tab-customers-panel" style="display: none;">
            <h2 class="section-title">Clientes Registrados (CRM) 👥</h2>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Registro histórico de clientes validados a través de su número de teléfono.</p>
            
            <!-- Buscador interactivo de clientes CRM -->
            <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; max-width: 450px;">
                <div style="position: relative; flex-grow: 1;">
                    <input type="text" id="crm-search-input" class="form-control" placeholder="Escribe el teléfono o nombre..." style="padding-left: 2.5rem; width: 100%;" onkeypress="handleCRMSearchKeyPress(event)">
                    <i data-lucide="phone" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--text-muted);"></i>
                </div>
                <button type="button" class="btn-checkout" onclick="filterCRMTable()" style="padding: 0.75rem 1.5rem; font-size: 0.85rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap; background: var(--accent); color: var(--bg-dark); cursor: pointer; border: none; transition: background 0.2s;" onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
                    <i data-lucide="search" style="width: 16px; height: 16px;"></i>
                    Buscar Cliente
                </button>
            </div>

            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Teléfono (ID)</th>
                            <th>Nombre Completo</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td><span style="color: var(--accent); font-weight: 700; font-size: 1.05rem;">📞 {{ $customer->phone }}</span></td>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td><span style="color: var(--text-muted);">{{ $customer->created_at->format('d/m/Y H:i') }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center; padding:4rem 0; color:var(--text-muted);">
                                    <i data-lucide="users" style="width:48px; height:48px; margin-bottom:0.5rem; opacity:0.5; display:block; margin-left:auto; margin-right:auto;"></i>
                                    <p>No hay clientes registrados en la base de datos todavía.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> <!-- Fin de tab-customers-panel -->

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tab switcher
    function switchTab(tab) {
        const pedidosPanel = document.getElementById('tab-pedidos-panel');
        const customersPanel = document.getElementById('tab-customers-panel');
        const pedidosBtn = document.getElementById('tab-pedidos-btn');
        const customersBtn = document.getElementById('tab-customers-btn');

        if (tab === 'pedidos') {
            pedidosPanel.style.display = 'block';
            customersPanel.style.display = 'none';
            pedidosBtn.classList.add('active');
            customersBtn.classList.remove('active');
        } else {
            pedidosPanel.style.display = 'none';
            customersPanel.style.display = 'block';
            customersBtn.classList.add('active');
            pedidosBtn.classList.remove('active');
        }
    }

    // Live Order Polling
    let latestOrderId = {{ $orders->first() ? $orders->first()->id : 0 }};
    
    function checkNewOrders() {
        fetch("{{ route('admin.check-new-orders') }}")
            .then(response => response.json())
            .then(data => {
                if (data.latest_id > latestOrderId) {
                    // New order detected!
                    latestOrderId = data.latest_id;
                    playNewOrderChime();
                    
                    // Reload page after chime finishes
                    setTimeout(() => {
                        location.reload();
                    }, 1600);
                }
            })
            .catch(err => console.error("Error polling new orders", err));
    }

    // Web Audio API Synthesizer Chime
    function playNewOrderChime() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            
            // Tone 1: D5
            let osc1 = audioCtx.createOscillator();
            let gain1 = audioCtx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
            gain1.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.8);
            osc1.connect(gain1);
            gain1.connect(audioCtx.destination);
            osc1.start();
            osc1.stop(audioCtx.currentTime + 0.8);
            
            // Tone 2: A5 (Delayed by 0.15s)
            setTimeout(() => {
                let osc2 = audioCtx.createOscillator();
                let gain2 = audioCtx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880.00, audioCtx.currentTime); // A5
                gain2.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 1.2);
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.start();
                osc2.stop(audioCtx.currentTime + 1.2);
            }, 150);
        } catch(e) {
            console.log("Audio synthesis not allowed or supported in this browser", e);
        }
    }

    // Start polling every 8 seconds
    setInterval(checkNewOrders, 8000);

    // CRM Customer Table Real-time Search Logic
    function filterCRMTable() {
        const query = document.getElementById('crm-search-input').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#tab-customers-panel tbody tr');
        
        rows.forEach(row => {
            // Ignorar la fila de "no hay clientes" si existe
            const emptyCell = row.querySelector('td[colspan]');
            if (emptyCell) {
                return;
            }

            const phoneCell = row.cells[0] ? row.cells[0].textContent.toLowerCase() : '';
            const nameCell = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';

            // Limpiamos emojis o caracteres extras en el teléfono al buscar
            const cleanPhone = phoneCell.replace(/[^0-9]/g, '');
            const cleanQuery = query.replace(/[^0-9a-zA-Záéíóúñ]/g, '');

            if (phoneCell.includes(query) || nameCell.includes(query) || cleanPhone.includes(cleanQuery)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function handleCRMSearchKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            filterCRMTable();
        }
    }
    
    // Si el usuario borra todo el input, se restauran todas las filas automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('crm-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    filterCRMTable();
                }
            });
        }
    });

    // Filtro interactivo por Estado de Órdenes
    function filterOrders(status) {
        const buttons = ['all', 'pending', 'preparing', 'delivered'];
        buttons.forEach(btn => {
            const el = document.getElementById(`filter-${btn}-btn`);
            if (btn === status) {
                el.classList.add('active');
                el.style.background = 'rgba(220, 38, 38, 0.1)';
                el.style.color = 'var(--primary)';
            } else {
                el.style.background = '';
                el.style.color = 'var(--text-main)';
            }
        });

        const rows = document.querySelectorAll('#tab-pedidos-panel tbody tr');
        rows.forEach(row => {
            // Ignorar fila vacía
            if (row.querySelector('td[colspan]')) return;

            const badge = row.querySelector('.status-badge');
            if (!badge) return;

            if (status === 'all' || badge.classList.contains(status)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
