<?php

namespace App\Http\Livewire\Front\Quizzes;

use App\Models\Quiz;
use Livewire\Component;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class Show extends Component
{
    public Quiz $quiz;

    public Collection $questions;

    public Question $currentQuestion;

    public int $currentQuestionIndex = 0;

    public array $questionsAnswers = [];

    public int $startTimeSeconds = 0;

    public function mount(): void
    {
        $this->startTimeSeconds = now()->timestamp;

        $this->questions = Question::query()
            ->inRandomOrder()
            ->whereRelation('quizzes', 'id', $this->quiz->id)
            ->with('questionOptions')
            ->get();

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];

        for($i = 0; $i < $this->questionsCount; $i++) {
            $this->questionsAnswers[$i] = [];
        }
    }

    public function changeQuestion(): void
    {
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex >= $this->questionsCount) {
            $this->submit();
        }

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
    }

    public function getQuestionsCountProperty(): int
    {
        return $this->questions->count();
    }

    public function submit()
    {
        dd('submit');
    }

    public function render(): View
    {
        return view('livewire.front.quizzes.show');
    }
}
