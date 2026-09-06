<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_loads_with_products(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Mau pesan apa hari ini?')
            ->assertSee(Product::first()->name);
    }

    public function test_menu_page_loads_with_filter(): void
    {
        $this->get('/menu')
            ->assertOk()
            ->assertSee('Menu');

        $this->get('/menu?category=makanan')
            ->assertOk();

        $this->get('/menu?q=churros')
            ->assertOk()
            ->assertSee('Churros');
    }

    public function test_product_detail_page_loads(): void
    {
        $product = Product::first();

        $this->get("/produk/{$product->id}")
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_cart_and_checkout_pages_load(): void
    {
        $this->get('/cart')->assertOk()->assertSee('Keranjang');
        $this->get('/checkout')->assertOk()->assertSee('Checkout');
    }

    public function test_admin_can_login(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'varo@admin.com',
            'password' => 'admin1510',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs(Admin::first(), 'admin');
    }

    public function test_admin_can_manage_products(): void
    {
        $admin = Admin::first();

        $this->actingAs($admin, 'admin')
            ->post('/admin/products', [
                'name' => 'Test Mie Ayam',
                'category_id' => 1,
                'price' => 12000,
                'description' => 'Test',
                'status' => 'ready',
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Test Mie Ayam']);
    }

    public function test_admin_dashboard_is_protected(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }
}
