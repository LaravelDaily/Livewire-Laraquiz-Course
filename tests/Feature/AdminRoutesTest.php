<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanAndOthersCannotAccessQuestionsPage()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('questions'));

        $response->assertOk();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('questions'));

        $response->assertForbidden();

        $response = $this->get(route('questions'));

        $response->assertForbidden();
    }

    public function testAdminCanAndOthersCannotAccessQuizzesPage()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('quizzes'));

        $response->assertOk();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('quizzes'));

        $response->assertForbidden();

        $response = $this->get(route('quizzes'));

        $response->assertForbidden();
    }

    public function testAdminCanAndOthersCannotAccessTestsPage()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('tests'));

        $response->assertOk();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tests'));

        $response->assertForbidden();

        $response = $this->get(route('tests'));

        $response->assertForbidden();
    }
}
