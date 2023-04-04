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

    public function mount(Question $question): void
    {
        $this->question = $question;

        if ($this->question->exists) {
            $this->editing = true;
        }
    }

    public function save(): Redirector
    {
        $this->validate();

        $this->question->save();

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
        ];
    }
}
