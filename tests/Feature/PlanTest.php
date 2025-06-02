<?php

use App\Models\Plan;
use App\Models\PlanFeature;

test('can create a plan', function () {
    $planData = [
        'name' => 'Basic Plan',
        'monthly_price' => 29.99,
        'user_limit' => 5,
        'features' => [
            ['name' => 'Feature 1', 'description' => 'Description 1'],
            ['name' => 'Feature 2', 'description' => 'Description 2'],
        ]
    ];

    $response = $this->postJson('/api/plans', $planData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'monthly_price',
                'user_limit',
                'features' => [
                    '*' => [
                        'id',
                        'name',
                        'description'
                    ]
                ]
            ]
        ]);

    $this->assertDatabaseHas('plans', [
        'name' => 'Basic Plan',
        'monthly_price' => 29.99,
        'user_limit' => 5
    ]);
});

test('can list all plans', function () {
    Plan::factory()->count(3)->create();

    $response = $this->getJson('/api/plans');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'monthly_price',
                    'user_limit'
                ]
            ]
        ])
        ->assertJsonCount(3, 'data');
});

test('can show a plan', function () {
    $plan = Plan::factory()->create();

    $response = $this->getJson("/api/plans/{$plan->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'monthly_price',
                'user_limit'
            ]
        ]);
});

test('can update a plan', function () {
    $plan = Plan::factory()->create();

    $updateData = [
        'name' => 'Updated Plan',
        'monthly_price' => 39.99,
        'user_limit' => 10
    ];

    $response = $this->putJson("/api/plans/{$plan->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'monthly_price',
                'user_limit'
            ]
        ])
        ->assertJson([
            'data' => [
                'name' => 'Updated Plan',
                'monthly_price' => 39.99,
                'user_limit' => 10
            ]
        ]);
});

test('can delete a plan', function () {
    $plan = Plan::factory()->create();

    $response = $this->deleteJson("/api/plans/{$plan->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
});
