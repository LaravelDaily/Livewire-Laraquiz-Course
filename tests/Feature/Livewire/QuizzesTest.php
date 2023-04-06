<?php

namespace Tests\Feature\Livewire;

use App\Models\Quiz;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Quiz\QuizForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizzesTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanCreateQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuizForm::class)
            ->set('quiz.title', 'quiz title')
            ->call('save')
            ->assertHasNoErrors(['quiz.title', 'quiz.slug', 'quiz.description', 'quiz.published', 'quiz.public', 'questions'])
            ->assertRedirect(route('quizzes'));

        $this->assertDatabaseHas('quizzes', [
            'title' => 'quiz title',
        ]);
    }

    public function testTitleIsRequired()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuizForm::class)
            ->set('quiz.title', '')
            ->call('save')
            ->assertHasErrors(['quiz.title' => 'required']);
    }

    public function testAdminCanEditQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = Quiz::factory()->create();

        Livewire::test(QuizForm::class, [$quiz])
            ->set('quiz.title', 'new quiz')
            ->set('quiz.published', true)
            ->call('save')
            ->assertSet('quiz.published', true)
            ->assertHasNoErrors(['quiz.title', 'quiz.slug', 'quiz.description', 'quiz.published', 'quiz.public', 'questions']);

        $this->assertDatabaseHas('quizzes', [
            'title' => 'new quiz',
            'published' => true,
        ]);
    }
}
