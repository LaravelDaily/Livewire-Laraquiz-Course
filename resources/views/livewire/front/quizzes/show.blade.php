<div
    x-data="{ secondsLeft: {{ config('quiz.secondsPerQuestion') }} }"
    x-init="setInterval(() => { if (secondsLeft > 1) { secondsLeft--; } else { secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.changeQuestion(); } }, 1000);">

    <div class="mb-2">
        Time left for this question: <span x-text="secondsLeft" class="font-bold"></span> sec.
    </div>

    <span class="text-bold">Question {{ $currentQuestionIndex + 1 }} of {{ $this->questionsCount }}:</span>
    <h2 class="mb-4 text-2xl">{{ $currentQuestion->question_text }}</h2>

    @if ($currentQuestion->code_snippet)
        <pre class="mb-4 border-2 border-solid bg-gray-50 p-2">{{ $currentQuestion->code_snippet }}</pre>
    @endif

    @foreach($currentQuestion->questionOptions as $option)
        <div>
            <label for="option.{{ $option->id }}">
                <input type="radio"
                       id="option.{{ $option->id }}"
                       wire:model.defer="questionsAnswers.{{ $currentQuestionIndex }}"
                       name="questionsAnswers.{{ $currentQuestionIndex }}"
                       value="{{ $option->id }}">
                {{ $option->option }}
            </label>
        </div>
    @endforeach

    @if ($currentQuestionIndex < $this->questionsCount - 1)
        <div class="mt-4">
            <x-secondary-button x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.changeQuestion();">
                Next question
            </x-secondary-button>
        </div>
    @else
        <div class="mt-4">
            <x-primary-button wire:click.prevent="submit">Submit</x-primary-button>
        </div>
    @endif
</div>
