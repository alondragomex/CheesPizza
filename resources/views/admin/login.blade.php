@extends('layouts.app')

@section('title', 'Acceso Administrativo 🔒 - Cheese Pizza')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh; padding: 2rem 1rem;">
    <div class="product-card" style="width: 100%; max-width: 400px; padding: 2.5rem; background: rgba(25, 15, 15, 0.75); border: 1px solid var(--border-accent); border-radius: 20px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem; filter: drop-shadow(0 2px 8px rgba(245,158,11,0.3));">🔒</div>
            <h2 style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--text-main); margin-bottom: 0.25rem;">Panel de Control</h2>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Solo personal autorizado</p>
        </div>

        <form action="{{ route('admin.login.post') }}" method="POST" class="order-form">
            @csrf

            <div class="form-group">
                <label for="password" style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.4rem;">Contraseña de Acceso</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required style="width: 100%; text-align: center; letter-spacing: 0.1em; font-size: 1.1rem; padding: 0.85rem;">
            </div>

            <button type="submit" class="btn-checkout" style="width: 100%; margin-top: 1.75rem; background: var(--accent); color: var(--bg-dark); font-weight: 800; font-size: 1rem; border-radius: 8px;">
                Ingresar al Sistema 🔑
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('menu') }}" style="font-size: 0.85rem; color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
                ← Volver al Menú Principal
            </a>
        </div>
    </div>
</div>
@endsection
