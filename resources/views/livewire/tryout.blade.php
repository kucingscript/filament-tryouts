<div>
    <style>
        .active-question {
            border: 3px solid darkblue;
        }

        .no-hover:hover {
            background-color: transparent !important;
            color: inherit !important;
        }

        .disabled-radio {
            cursor: not-allowed;
        }

        .disable-button {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
    <div class="container mt-4">
        <div class="row">
            <h4>{{ $package->name }}</h4>
            <div class="col-md-8">
                <div id="question-container">

                    <div class="card question-card">
                        @if ($tryOut->finished_at == null)
                            <div class="countdown-timer mb-4 text-success" id="countdown">
                                Waktu tersisa: <span id="time">00:00:00</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <p class="card-text">{!! $currentPackageQuestion->question->question !!}</p>
                            @foreach ($currentPackageQuestion->question->options as $item)
                                <div class="form-check">
                                    <input class="form-check-input"
                                        wire:model="selectedAnswers.{{ $currentPackageQuestion->question_id }}"
                                        wire:click="saveAnswer({{ $currentPackageQuestion->question_id }}, {{ $item->id }})"
                                        type="radio"
                                        @if ($timeLeft <= 0) disabled
                                            class="disable-radio" @endif
                                        name="question" value="{{ $item->id }}" id="option-{{ $item->id }}"
                                        @if ($tryOutAnswers->isEmpty() || !$tryOutAnswers->contains('option_id', $item->id)) @else
                                            checked @endif>
                                    <label for="option-{{ $item->id }}"
                                        class="form-check-label">{!! $item->option_text !!}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card question-navigation">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Navigasi</h5>
                        <div class="btn-group-flex" role="group">
                            @foreach ($questions as $index => $item)
                                @php
                                    $isAnswered =
                                        isset($selectedAnswers[$item->question_id]) &&
                                        !is_null($selectedAnswers[$item->question_id]);
                                    $isActive = $currentPackageQuestion->question->id === $item->question_id;

                                @endphp
                                <div class="col-2 mb-2">
                                    <button type="button" @if ($timeLeft <= 0) disabled @endif
                                        wire:click="goToQuestion({{ $item->id }})"
                                        class="btn {{ $isAnswered ? 'btn-primary' : 'btn-outline-primary no-hover' }} {{ $isActive ? 'active-question' : '' }}">{{ $index + 1 }}</button>

                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary mt-3 w-100"
                            @click.prevent="if (confirm('Apakah anda yakin ingin mengirim jawaban ini?')) { $wire.submit(); }">
                            Submit
                        </button>

                    </div>
                </div>
            </div>
        </div>

        @session('message')
            <div class="alert alert-success text-center">
                {{ session('message') }} <a href="{{ url('admin/tryouts') }}">Lihat Hasil Pengerjaan</a>
            </div>
        @endsession

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = {{ $timeLeft }};
            startCountdown(timeLeft, document.getElementById('time'));
        });

        function startCountdown(duration, display) {
            let timer = duration,
                minutes, seconds;
            setInterval(function() {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = hours + ":" + minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = 0;
                }
            }, 1000);
        }
    </script>
</div>
