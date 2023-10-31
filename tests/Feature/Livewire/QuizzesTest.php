<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use App\Models\Quiz;
use Livewire\Livewire;
use App\Models\Question;
use App\Livewire\Quiz\QuizForm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizzesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanCreateQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuizForm::class)
            ->set('title', 'quiz title')
            ->call('save')
            ->assertHasNoErrors(['title', 'slug', 'description', 'published', 'public', 'questions'])
            ->assertRedirect(route('quizzes'));

        $this->assertDatabaseHas('quizzes', [
            'title' => 'quiz title',
        ]);
    }

    public function testTitleIsRequired()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuizForm::class)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }

    public function testAdminCanEditQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = Quiz::factory()
            ->has(Question::factory())
            ->create();

        Livewire::test(QuizForm::class, [$quiz])
            ->set('title', 'new quiz')
            ->set('published', true)
            ->call('save')
            ->assertSet('published', true)
            ->assertHasNoErrors(['title', 'slug', 'description', 'published', 'public', 'questions']);

        $this->assertDatabaseHas('quizzes', [
            'title' => 'new quiz',
            'published' => true,
        ]);
    }
}
