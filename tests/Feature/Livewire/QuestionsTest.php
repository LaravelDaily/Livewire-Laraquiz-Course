<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use Livewire\Livewire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Http\Livewire\Questions\QuestionForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanCreateQuestion()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuestionForm::class)
            ->set('question.question_text', 'very secret question')
            ->set('questionOptions.0.option', 'first answer')
            ->call('save')
            ->assertHasNoErrors(['question.question_text', 'question.code_snippet', 'question.answer_explanation', 'question.more_info_link', 'question.topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', [
            'question_text' => 'very secret question',
        ]);
    }

    public function testQuestionTextIsRequired()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuestionForm::class)
            ->set('question.question_text', '')
            ->call('save')
            ->assertHasErrors(['question.question_text' => 'required']);
    }

    public function testAdminCanEditQuestion()
    {
        $this->actingAs(User::factory()->admin()->create());

        $question = Question::factory()
            ->has(QuestionOption::factory())
            ->create();

        Livewire::test(QuestionForm::class, [$question])
            ->set('question.question_text', 'very secret question')
            ->call('save')
            ->assertHasNoErrors(['question.question_text', 'question.code_snippet', 'question.answer_explanation', 'question.more_info_link', 'question.topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', [
            'question_text' => 'very secret question',
        ]);
    }
}
