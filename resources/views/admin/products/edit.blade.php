@extends('layouts.app')

@section('title', 'Editar Producto - Admin 🍕')

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
    <div class="admin-card">
        <h2 class="admin-card-title">Editar Producto: <span style="color:var(--accent);">{{ $product->name }}</span> 🍕🍔</h2>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="order-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="name">Nombre del Producto</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej. Pizza Mexicana" required value="{{ old('name', $product->name) }}">
                </div>

                <div class="form-group">
                    <label for="price">Precio ($ MXN)</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="Ej. 189.00" required step="0.01" min="0" value="{{ old('price', $product->price) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                <div class="form-group">
                    <label for="category">Categoría</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="pizza" {{ old('category', $product->category) == 'pizza' ? 'selected' : '' }}>Pizza 🍕</option>
                        <option value="additional" {{ old('category', $product->category) == 'additional' ? 'selected' : '' }}>Adicional / Snack 🍟</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_file">Subir Nueva Imagen desde Archivos 📁</label>
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                </div>
            </div>

            @if($product->image_url)
                <div style="margin-top: 1.25rem; display: flex; align-items: center; gap: 1.25rem; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 0.85rem; border-radius: 12px; backdrop-filter: blur(5px);">
                    <img src="{{ str_starts_with($product->image_url, '/storage/') ? asset($product->image_url) : $product->image_url }}" alt="Actual" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Imagen actual asignada:</div>
                        <code style="font-size: 0.75rem; color: var(--accent); word-break: break-all;">{{ $product->image_url }}</code>
                    </div>
                </div>
            @endif

            <div class="form-group" style="margin-top: 1rem;">
                <label for="description">Descripción / Ingredientes</label>
                <textarea name="description" id="description" rows="4" class="form-control" placeholder="Escribe los ingredientes principales o detalles de este producto..." required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end;">
                <a href="{{ route('admin.products.index') }}" class="btn-action-text" style="padding: 0.75rem 1.5rem;">
                    Cancelar
                </a>
                <button type="submit" class="btn-action-text primary" style="padding: 0.75rem 1.5rem;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
