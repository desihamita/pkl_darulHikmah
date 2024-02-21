@extends('layouts.student-layout')

@section('content')
    @php
    $time = explode(':', $exam[0]['time']);
    @endphp

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card card-success">
                        <h4 class="card-header text-center time">{{ $exam[0]['time'] }}</h4>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Soal</h5>
                        </div>
                        <div class="card-body">
                            <div class="question-buttons">
                                @foreach ($qna as $item)
                                <button type="button" class="btn btn-outline-success btn-question" data-question-id="{{ $item['question']['id'] }}">
                                    {{ $loop->iteration }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                Soal Ujian
                            </h5>
                        </div>
                        <div class="card-body">
                            @php $qcount = 1; @endphp
                            @if ($success == true)
                                @if (count($qna) > 0)
                                <form action="{{ route('examSubmit')}}" method="post" id="exam_form" class="exam_form">
                                    @csrf
                                    <input type="hidden" name="exam_id" value="{{ $exam[0]['id'] }}">
                                    <div class="question-container">
                                        @foreach ($qna as $item)
                                        <div class="question-card" id="question-card-{{ $loop->iteration }}" @if($loop->iteration > 1) style="display: none;" @endif>

                                            <h5>{{ $loop->iteration }}. {{ $item['question']['question'] }}</h5>

                                            <input type="hidden" name="q[]" value="{{ $item['question']['id'] }}">

                                            <input type="hidden" name="ans_{{ $item['question']['id'] }}"
                                                id="ans_{{ $item['question']['id'] }}">

                                            @php $alphabet = range('A', 'Z'); @endphp

                                            @foreach ($item['question']['answers'] as $key => $answer)
                                                @if (isset($alphabet[$key]))
                                                    @php $letter = $alphabet[$key]; @endphp
                                                @else
                                                    @php $letter = ''; @endphp
                                                @endif

                                                <p>
                                                    <input type="radio" name="radio_{{ $item['question']['id'] }}" value="{{ $answer['id'] }}" data-id="{{ $item['question']['id'] }}" class="select_ans ml-5">
                                                    {{ $letter }}. {{ $answer['answer']}}
                                                </p>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-success btn-previous" onclick="prevQuestion()" style="display: none;">Previous</button>
                                        <button type="button" class="btn btn-success btn-next" onclick="nextQuestion()">Next</button>

                                        <input type="submit" class="btn btn-primary btn-submit" style="display: none;">
                                    </div>

                                </form>
                                @else
                                    <h3 class="text-center" style="color:red">Questions & Answers not available</h3>
                            @endif
                            @else
                                <h3 class="text-center" style="color:red">{{ $msg }}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('form').submit(function() {
                var currentQuestionId = $(this).closest(".question-card").data("question-id");

                $('.select_ans:checked').each(function() {
                    var no = $(this).attr('data-id');
                    $('#ans_' + no).val($(this).val());
                })

                showNextQuestion(currentQuestionId);
            });

            // var time = @json($time);
            // $('.time').text(time[0] + ':' + time[1] + ':00');

            // var seconds = 1;
            // var hours = parseInt(time[0]);
            // var minutes = parseInt(time[1]);

            // var timer = setInterval(() => {
            //     if (hours == 0 && minutes == 0 && seconds == 0) {
            //         clearInterval(timer);
            //         $('#exam_form').submit();
            //     }
            //     console.log(hours + " -:- " + minutes + " -:- " + seconds)
            //     if (seconds <= 0) {
            //         minutes--;
            //         seconds = 59;
            //     }
            //     if (minutes <= 0 && hours != 0) {
            //         hours--;
            //         minutes = 59;
            //         seconds = 59;
            //     }

            //     let tempHours = hours.toString().length > 1 ? hours : '0' + hours;
            //     let tempMinutes = minutes.toString().length > 1 ? minutes : '0' + minutes;
            //     let tempSeconds = seconds.toString().length > 1 ? seconds : '0' + seconds;

            //     $('.time').text(tempHours + ':' + tempMinutes + ':' + tempSeconds + '');
            //     seconds--;
            // }, 1000);

            $(".btn-next").on("click", showNextQuestion);
            $(".btn-previous").on("click", showPreviousQuestion);

            $(".btn-question").on("click", function () {
                var questionNumber = $(this).text().trim();
                showQuestionByNumber(questionNumber);
            });
        });
        function showNextQuestion() {
            var currentQuestion = $(".question-card:visible");
            var nextQuestion = currentQuestion.next(".question-card");

            if (nextQuestion.length > 0) {
                currentQuestion.hide();
                nextQuestion.show();
                $(".btn-previous").show();
                $(".btn-submit").hide();
            } else {
                $(".btn-submit").show();
                $(".btn-next").hide();
                $(".btn-previous").show();
            }
        }

        function showPreviousQuestion() {
            var currentQuestion = $(".question-card:visible");
            var prevQuestion = currentQuestion.prev(".question-card");

            if (prevQuestion.length > 0) {
                currentQuestion.hide();
                prevQuestion.show();
                $(".btn-next").show();
                $(".btn-submit").hide();
            } else {
                $(".btn-previous").hide();
            }
        }

        function showQuestionByNumber(questionNumber) {
            $(".question-card").hide();
            $("#question-card-" + questionNumber).show();

            // Show/hide navigation buttons based on the current question
            var isFirstQuestion = questionNumber === 1;
            var isLastQuestion = questionNumber === {{ count($qna) }};

            $(".btn-previous").toggle(!isFirstQuestion);
            $(".btn-next").toggle(!isLastQuestion);
            $(".btn-submit").toggle(isLastQuestion);
        }

        function isValid() {
            var result = true;
            var qlength = parseInt("{{ count($qna) }}") - 1;

            for (let i = 1; i <= qlength; i++) {
                if ($('#ans_' + i).val() == "") {
                    result = false;
                    $('#ans_' + i).parent().append(
                        '<span class="error_msg" style="color:red;">Please select answer for question ' + i + '</span>');
                    setTimeout(function() {
                        $('.error_msg').remove();
                    }, 5000);
                }
            }
            return result;
        }
    </script>
@endsection
