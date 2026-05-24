@extends('layouts.app')

@section('title', 'CRUD Productos - Admin 🍕')

@section('content')
<div class="admin-grid">
    
    <!-- Sidebar de Navegación -->
    <aside class="admin-sidebar">
        <h3 style="font-family: var(--font-serif); margin-bottom: 1.5rem; color: var(--accent);">Panel de Control</h3>
        <ul class="admin-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="admin-menu-link">
                    <i data-lucide="shopping-bag" style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:8px;"></i>
                    Pedidos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="admin-menu-link active">
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
        </ul>
    </aside>

    <!-- Área de Trabajo Principal -->
    <div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
            <h2 class="section-title" style="margin-bottom:0;">Gestión de Productos 🍕🍟</h2>
            <a href="{{ route('admin.products.create') }}" class="btn-action-text primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Agregar Producto
            </a>
        </div>

        <!-- Tabla de Productos -->
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Descripción</th>
                        <th style="width: 120px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width:50px; height:50px; object-fit:cover; border-radius:8px; border: 1px solid var(--border-color);">
                                @else
                                    <div style="width:50px; height:50px; background:#221515; display:flex; align-items:center; justify-content:center; border-radius:8px;">🍕</div>
                                @endif
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>
                                <span class="product-badge {{ $product->category == 'additional' ? 'additional' : '' }}" style="position:relative; top:0; right:0; font-size:0.7rem; padding: 0.2rem 0.5rem;">
                                    {{ $product->category == 'pizza' ? 'Pizza' : 'Adicional' }}
                                </span>
                            </td>
                            <td><span style="color:var(--accent); font-weight:700;">${{ number_format($product->price, 2) }}</span></td>
                            <td style="color:var(--text-muted); font-size:0.85rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $product->description }}">
                                {{ $product->description }}
                            </td>
                            <td>
                                <div class="btn-group" style="justify-content: center;">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action edit" title="Editar Producto">
                                        <i data-lucide="pencil" style="width: 16px; height: 16px;"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Eliminar Producto">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:4rem 0; color:var(--text-muted);">
                                <i data-lucide="pizza" style="width:48px; height:48px; margin-bottom:0.5rem; opacity:0.5; display:block; margin-left:auto; margin-right:auto;"></i>
                                <p>No hay productos registrados en el menú todavía.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
