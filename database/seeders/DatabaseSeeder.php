<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeding default pizzas
        Product::firstOrCreate(
            ['name' => 'Pizza de Pepperoni Clásica'],
            [
                'description' => 'Deliciosa salsa de tomate, abundante queso mozzarella y rebanadas de pepperoni premium crujientes.',
                'price' => 189.00,
                'category' => 'pizza',
                'image_url' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=500&auto=format&fit=crop',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Pizza Hawaiana Especial'],
            [
                'description' => 'La combinación perfecta de jamón de pavo ahumado, piña dulce jugosa y doble porción de queso mozzarella.',
                'price' => 199.00,
                'category' => 'pizza',
                'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&auto=format&fit=crop',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Pizza Suprema Vegetariana'],
            [
                'description' => 'Pimientos frescos, cebolla morada, champiñones rebanados, aceitunas negras y jitomate cherry con orégano.',
                'price' => 219.00,
                'category' => 'pizza',
                'image_url' => 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?w=500&auto=format&fit=crop',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Pizza Tres Quesos Italiana'],
            [
                'description' => 'Una mezcla artesanal de queso mozzarella, parmesano rallado y queso provolone suave sobre salsa marinara especial.',
                'price' => 209.00,
                'category' => 'pizza',
                'image_url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500&auto=format&fit=crop',
            ]
        );

        // Seeding default additional items
        Product::firstOrCreate(
            ['name' => 'Papas Gajo Sazonadas'],
            [
                'description' => 'Papas cortadas en gajo sazonadas con paprika y finas hierbas, crujientes por fuera y suaves por dentro.',
                'price' => 79.00,
                'category' => 'additional',
                'image_url' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=500&auto=format&fit=crop',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Boneless BBQ Crunch'],
            [
                'description' => 'Trozos jugosos de pechuga de pollo empanizados, bañados en nuestra salsa BBQ dulce y acompañados de aderezo ranch.',
                'price' => 129.00,
                'category' => 'additional',
                'image_url' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=500&auto=format&fit=crop',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Pan con Ajo y Parmesano'],
            [
                'description' => 'Rebanadas de pan artesanal horneadas al momento con mantequilla de ajo, queso parmesano y perejil fresco.',
                'price' => 69.00,
                'category' => 'additional',
                'image_url' => 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?w=500&auto=format&fit=crop',
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Refresco Familiar 2L'],
            [
                'description' => 'Refresco de cola helado de 2 litros, ideal para compartir en familia.',
                'price' => 45.00,
                'category' => 'additional',
                'image_url' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop',
            ]
        );


        // Seeding default customers for CRM demonstration
        \App\Models\Customer::firstOrCreate(
            ['phone' => '4492846027'],
            ['name' => 'Aoondra']
        );

        \App\Models\Customer::firstOrCreate(
            ['phone' => '5512345678'],
            ['name' => 'Alejandro Mendoza']
        );

        \App\Models\Customer::firstOrCreate(
            ['phone' => '5598765432'],
            ['name' => 'Sofía Rodríguez']
        );
    }
}
