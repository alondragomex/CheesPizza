@extends('layouts.app')

@section('title', 'Agregar Producto - Admin 🍕')

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
        <h2 class="admin-card-title">Agregar Nuevo Producto 🍕🍔</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" class="order-form" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="name">Nombre del Producto</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej. Pizza Mexicana" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="price">Precio ($ MXN)</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="Ej. 189.00" required step="0.01" min="0" value="{{ old('price') }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                <div class="form-group">
                    <label for="category">Categoría</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="pizza" {{ old('category') == 'pizza' ? 'selected' : '' }}>Pizza 🍕</option>
                        <option value="additional" {{ old('category') == 'additional' ? 'selected' : '' }}>Adicional / Snack 🍟</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_file">Subir Imagen desde mis Archivos 📁</label>
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*" style="padding-top: 0.5rem; padding-bottom: 0.5rem;" required>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label for="description">Descripción / Ingredientes</label>
                <textarea name="description" id="description" rows="4" class="form-control" placeholder="Escribe los ingredientes principales o detalles de este producto..." required>{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end;">
                <a href="{{ route('admin.products.index') }}" class="btn-action-text" style="padding: 0.75rem 1.5rem;">
                    Cancelar
                </a>
                <button type="submit" class="btn-action-text primary" style="padding: 0.75rem 1.5rem;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
