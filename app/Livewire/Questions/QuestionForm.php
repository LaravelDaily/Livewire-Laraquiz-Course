<?php

namespace App\Livewire\Questions;

use Livewire\Component;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class QuestionForm extends Component
{
    public ?Question $question = null;

    public string $question_text = '';
    public string|null $code_snippet = '';
    public string|null $answer_explanation = '';
    public string|null $more_info_link = '';

    public bool $editing = false;

    public array $questionOptions = [];

    public function mount(Question $question): void
    {
        if ($question->exists) {
            $this->question = $question;
            $this->editing = true;
            $this->question_text = $question->question_text;
            $this->code_snippet = $question->code_snippet;
            $this->answer_explanation = $question->answer_explanation;
            $this->more_info_link = $question->more_info_link;

            foreach ($question->questionOptions as $option) {
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

    public function save(): Redirector|RedirectResponse
    {
        $this->validate();

        if (empty($this->question)) {
            $this->question = Question::create($this->only(['question_text', 'code_snippet', 'answer_explanation', 'more_info_link']));
        } else {
            $this->question->update($this->only(['question_text', 'code_snippet', 'answer_explanation', 'more_info_link']));
        }

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
            'question_text' => [
                'string',
                'required',
            ],
            'code_snippet' => [
                'string',
                'nullable',
            ],
            'answer_explanation' => [
                'string',
                'nullable',
            ],
            'more_info_link' => [
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
