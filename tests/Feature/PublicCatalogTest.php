<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_featured_products(): void
    {
        $category = Category::factory()->create();
        $featured = Product::factory()->create([
            'category_id' => $category->id,
            'is_featured' => true,
        ]);
        Product::factory()->create(['category_id' => $category->id, 'is_featured' => false]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($featured->name);
    }

    public function test_catalog_lists_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_catalog_filters_by_category(): void
    {
        $category = Category::factory()->create(['slug' => 'rotary-dryer']);
        $otherCategory = Category::factory()->create(['slug' => 'pelet']);
        $included = Product::factory()->create(['category_id' => $category->id]);
        $excluded = Product::factory()->create(['category_id' => $otherCategory->id]);

        $this->get(route('products.index', ['category' => 'rotary-dryer']))
            ->assertOk()
            ->assertSee($included->name)
            ->assertDontSee($excluded->name);
    }

    public function test_catalog_searches_products(): void
    {
        $category = Category::factory()->create();
        $found = Product::factory()->create(['category_id' => $category->id, 'name' => 'Mesin Rotary Dryer 1 Ton']);
        $other = Product::factory()->create(['category_id' => $category->id, 'name' => 'Mesin Pelet']);

        $this->get(route('products.index', ['q' => 'Rotary']))
            ->assertOk()
            ->assertSee($found->name)
            ->assertDontSee($other->name);
    }

    public function test_product_detail_shows_specifications_and_whatsapp_link(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product->specifications()->create(['spec_key' => 'Kapasitas', 'spec_value' => '1 ton/jam']);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('Kapasitas')
            ->assertSee('wa.me/');
    }

    public function test_product_detail_returns_404_for_unknown_slug(): void
    {
        $this->get(route('products.show', 'tidak-ada'))
            ->assertNotFound();
    }
}
