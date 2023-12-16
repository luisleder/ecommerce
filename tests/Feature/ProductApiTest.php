<?php
namespace Tests\Feature;

use Tests\TestCase;
use Tests\Helper;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class ProductApiTest extends TestCase
{
    use RefreshDatabase, Helper;

     /** @var User */
     private $user;
     private $access_token;

     protected function setUp(): void
     {
         parent::setUp();

         $this->createPersonalClient();
 
         // Create a user (or use factory) and authenticate with Passport
         $this->user = User::factory()->create();
         $this->access_token = $this->user->createToken("test token")->accessToken;
     }

    /** @test */
    public function it_can_create_a_product()
    {
        $productData = Product::factory()->make()->toArray();

        $response = $this->postJson('/api/product', $productData, [
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [],
        ]);

    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create();

        $faker = Faker::create();

        $updatedData = [
            'name' => $faker->word(),
            'description' => $faker->text(),
            'image' => $faker->imageUrl(format: 'jpg'),
            'brand' => $faker->company(),
            'price' => $faker->randomFloat(2,100,10000),
            'price_sale' => $faker->randomFloat(2,100,10000),
            'category' => $faker->jobTitle(),
            'stock' => $faker->randomDigitNotNull(),
        ];;

        $response = $this->putJson("/api/product/{$product->id}", $updatedData, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertStatus(204);

        // Check if the product was actually updated in the database
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => $updatedData["name"]]);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/product/{$product->id}",  headers: [
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertStatus(204);

        // Check if the product was actually deleted from the database
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_can_get_all_products()
    {
        Product::factory(5)->create();

        $response = $this->getJson('/api/product', headers: [
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [],
            'links' => [],
            'meta' => [],
        ]);

    }

    /** @test */
    public function it_can_find_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/product/{$product->id}", headers: [
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [],
        ]);

    }

    /** @test */
    public function it_returns_not_found_for_nonexistent_product()
    {
        $response = $this->getJson('/api/product/9999', headers: [
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Product not found.']);
    }


    /** @test */
    public function it_returns_not_found_for_nonexistent_update_product()
    {
        $faker = Faker::create();

        $updatedData = [
            'name' => $faker->word(),
            'description' => $faker->text(),
            'image' => $faker->imageUrl(format: 'jpg'),
            'brand' => $faker->company(),
            'price' => $faker->randomFloat(2,100,10000),
            'price_sale' => $faker->randomFloat(2,100,10000),
            'category' => $faker->jobTitle(),
            'stock' => $faker->randomDigitNotNull(),
        ];;

        $response = $this->putJson("/api/product/999999", $updatedData, [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Product not found.']);

    }

    /** @test */
    public function it_handles_request_validation_errors()
    {
        $invalidData = ['name' => '']; // Assuming 'name' is a required field

        $response = $this->postJson('/api/product', $invalidData, headers: [
            'Authorization' => 'Bearer '.$this->access_token
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
}
