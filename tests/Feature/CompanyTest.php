<?php

use App\Models\Company;
use App\Models\Plan;

test('can create a company', function () {
    $companyData = [
        'name' => 'Acme Corp',
        'email' => 'contact@acme.com'
    ];

    $response = $this->postJson('/api/companies', $companyData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email'
            ]
        ]);

    $this->assertDatabaseHas('companies', [
        'name' => 'Acme Corp',
        'email' => 'contact@acme.com'
    ]);
});

test('can list all companies', function () {
    Company::factory()->count(3)->create();

    $response = $this->getJson('/api/companies');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ])
        ->assertJsonCount(3, 'data');
});

test('can show a company', function () {
    $company = Company::factory()->create();

    $response = $this->getJson("/api/companies/{$company->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email'
            ]
        ]);
});

test('can update a company', function () {
    $company = Company::factory()->create();

    $updateData = [
        'name' => 'Updated Company',
        'email' => 'updated@company.com'
    ];

    $response = $this->putJson("/api/companies/{$company->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email'
            ]
        ])
        ->assertJson([
            'data' => [
                'name' => 'Updated Company',
                'email' => 'updated@company.com'
            ]
        ]);
});

test('can delete a company', function () {
    $company = Company::factory()->create();

    $response = $this->deleteJson("/api/companies/{$company->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('companies', ['id' => $company->id]);
});

test('can subscribe to a plan', function () {
    $company = Company::factory()->create();
    $plan = Plan::factory()->create();

    $response = $this->postJson("/api/companies/{$company->id}/subscribe", [
        'plan_id' => $plan->id
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'active_subscription' => [
                    'id',
                    'plan_id',
                    'status',
                    'start_date',
                    'end_date'
                ]
            ]
        ]);
});

test('can cancel subscription', function () {
    $company = Company::factory()->create();
    $plan = Plan::factory()->create();

    // First subscribe to a plan
    $this->postJson("/api/companies/{$company->id}/subscribe", [
        'plan_id' => $plan->id
    ]);

    // Then cancel the subscription
    $response = $this->postJson("/api/companies/{$company->id}/cancel-subscription");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email'
            ]
        ]);
});
