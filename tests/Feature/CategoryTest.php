<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_admin_can_create_category()
    {
        $name = "Testing Category";

        $response = $this->post('/admin/categories', [
            'name' => $name,
        ]);

        // test if the response status is 201 Created
        $response->assertStatus(201);

        // test if the response data contains the value true
        $response->assertJson(['success' => true]);

        // test the existence of a specific record in the table
        $this->assertDatabaseHas('categories', [
            'name' => $name,
        ]);
    }

    /** @test */
    public function test_create_category_requires_name()
    {
        $response = $this->post('/admin/categories', []);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function test_category_name_must_be_unique()
    {
        Category::factory()->create([
            'name' => 'Unique Category',
        ]);

        $response = $this->post('/admin/categories', [
            'name' => 'Unique Category',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function test_admin_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->get('/admin/categories');

        $response->assertStatus(200);

        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create();

        $newName = "Updated Category";

        $response = $this->put("/admin/categories/{$category->id}", [
            'name' => $newName,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // assert database updated
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $newName,
        ]);
    }

    /** @test */
    public function test_update_category_requires_name()
    {
        $category = Category::factory()->create();

        $response = $this->put("/admin/categories/{$category->id}", []);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/admin/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // assert database no longer has the record
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
