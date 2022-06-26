<?php

namespace Tests\Feature\Models;

use App\Enums\SubscriberState;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $subscriber;

    public function setUp(): void
    {
        parent::setUp();

        $this->subscriber = Subscriber::factory()->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_subscribers_can_be_retrieved()
    {
        $response = $this->get('/api/subscribers');

        $response->assertStatus(200);
    }

    public function test_a_subscriber_can_be_retrieved()
    {
        $response = $this->get('/api/subscribers/'.$this->subscriber->id);

        $response->assertStatus(200);
        $response->assertSee('name', 'email', 'state');
    }

    public function test_a_subscriber_can_be_created()
    {
        $response = $this->post('/api/subscribers', [
            'name' => $this->faker->name,
            'email' => 'test.email@gmail.com',
            'state' => SubscriberState::Unsubscribed->value
        ]);

        $response->assertStatus(201);
    }

    public function test_a_subscriber_can_be_updated()
    {
        $response = $this->patch('/api/subscribers/'.$this->subscriber->id, [
            'name' => $this->faker->name,
            'email' => 'test@gmail.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_a_subscriber_can_be_deleted()
    {
        $response = $this->delete('/api/subscribers/'.$this->subscriber->id);

        $response->assertStatus(200);
    }
}
