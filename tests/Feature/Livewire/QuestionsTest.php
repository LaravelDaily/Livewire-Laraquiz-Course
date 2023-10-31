<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Livewire\Questions\QuestionForm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanCreateQuestion()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuestionForm::class)
            ->set('question_text', 'very secret question')
            ->set('questionOptions.0.option', 'first answer')
            ->call('save')
            ->assertHasNoErrors(['question_text', 'code_snippet', 'answer_explanation', 'more_info_link', 'topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', [
            'question_text' => 'very secret question',
        ]);
    }

    public function testQuestionTextIsRequired()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuestionForm::class)
            ->set('question_text', '')
            ->call('save')
            ->assertHasErrors(['question_text' => 'required']);
    }

    public function testAdminCanEditQuestion()
    {
        $this->actingAs(User::factory()->admin()->create());

        $question = Question::factory()
            ->has(QuestionOption::factory())
            ->create();

        Livewire::test(QuestionForm::class, [$question])
            ->set('question_text', 'very secret question')
            ->call('save')
            ->assertHasNoErrors(['question_text', 'code_snippet', 'answer_explanation', 'more_info_link', 'topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', [
            'question_text' => 'very secret question',
        ]);
    }
}
