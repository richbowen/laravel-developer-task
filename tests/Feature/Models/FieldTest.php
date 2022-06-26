<?php

namespace Tests\Feature\Models;

use App\Enums\FieldType;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FieldTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $field;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = Field::factory()->create();
    }

    public function test_fields_can_be_retrieved()
    {
        $response = $this->get('/api/fields');

        $response->assertStatus(200);
    }

    public function test_a_field_can_be_retrieved()
    {
        $response = $this->get('/api/fields/'.$this->field->id);

        $response->assertStatus(200);
        $response->assertSee('title', 'type');
    }

    public function test_a_field_can_be_created()
    {
        $response = $this->post('/api/fields', [
            'title' => $this->faker->name,
            'type' => FieldType::Date->value
        ]);

        $response->assertStatus(201);
    }

    public function test_a_field_can_be_updated()
    {
        $response = $this->patch('/api/fields/'.$this->field->id, [
            'title' => $this->faker->name,
            'type' => FieldType::Boolean->value,
        ]);

        $response->assertStatus(200);
    }

    public function test_a_subscriber_can_be_deleted()
    {
        $response = $this->delete('/api/fields/'.$this->field->id);

        $response->assertStatus(200);
    }
}
