<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $response = $this->postJson('/api/admin/categories', [
            'name' => 'Testing Category'
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category created successfully'
                 ]);
    }

    public function test_create_category_requires_name()
    {
        $response = $this->postJson('/api/admin/categories', []);

        $response->assertStatus(422) // Unprocessable Entity
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_create_category_unique_name()
    {
        $category = Category::factory()->create([
            'name' => 'Unique Category'
        ]);

        $response = $this->postJson('/api/admin/categories', [
            'name' => 'Unique Category'
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         ['id', 'name', 'created_at', 'updated_at']
                     ],
                     'status'
                 ]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/admin/categories/{$category->id}", [
            'name' => 'Updated Category'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category updated successfully'
                 ]);
    }

    public function test_update_category_requires_name()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/admin/categories/{$category->id}", []);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/admin/categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Category deleted successfully'
                 ]);
    }
}
