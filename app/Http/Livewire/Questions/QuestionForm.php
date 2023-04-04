<?php

namespace App\Http\Livewire\Questions;

use Livewire\Component;
use Livewire\Redirector;
use App\Models\Question;
use Illuminate\Contracts\View\View;

class QuestionForm extends Component
{
    public Question $question;

    public bool $editing = false;

    public array $questionOptions = [];

    public function mount(Question $question): void
    {
        $this->question = $question;

        if ($this->question->exists) {
            $this->editing = true;

            foreach ($this->question->questionOptions as $option) {
                $this->questionOptions[] = [
                    'id'      => $option->id,
                    'option'  => $option->option,
                    'correct' => $option->correct,
                ];
            }
        }
    }

    public function addQuestionsOption(): void
    {
        $this->questionOptions[] = [
            'option' => '',
            'correct' => false
        ];
    }

    public function removeQuestionsOption(int $index): void
    {
        unset($this->questionOptions[$index]);
        $this->questionOptions = array_values(($this->questionOptions));
    }

    public function save(): Redirector
    {
        $this->validate();

        $this->question->save();

        $this->question->questionOptions()->delete();

        foreach ($this->questionOptions as $option) {
            $this->question->questionOptions()->create($option);
        }

        return to_route('questions');
    }

    public function render(): View
    {
        return view('livewire.questions.question-form');
    }

    protected function rules(): array
    {
        return [
            'question.question_text' => [
                'string',
                'required',
            ],
            'question.code_snippet' => [
                'string',
                'nullable',
            ],
            'question.answer_explanation' => [
                'string',
                'nullable',
            ],
            'question.more_info_link' => [
                'url',
                'nullable',
            ],
            'questionOptions' => [
                'required',
                'array',
            ],
            'questionOptions.*.option' => [
                'required',
                'string',
            ],
        ];
    }
}
