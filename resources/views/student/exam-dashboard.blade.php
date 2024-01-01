@extends('layout.layout-common')

@section('space-work')
    @php
        $time = explode(':', $exam[0]['time']);
    @endphp

    <div class="container">
        <p style="color:black">welcome, {{ Auth::user()->name }}</p>
        <h1 class="text-center">{{ $exam[0]['exam_name'] }}</h1>
        <h4 class="text-right time">{{ $exam[0]['time'] }}</h4>

        @php $qcount = 1; @endphp
        @if ($success == true)

            @if (count($qna) > 0)
                <form action="{{ route('examSubmit')}}" method="post" class="mb-5" onsubmit="return isValid()">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam[0]['id'] }}">
                    @foreach ($qna as $item)
                        <div>
                            <h5>Q{{ $loop->iteration }}. {{ $item['question']['question'] }}</h5>
                            <input type="hidden" name="q[]" value="{{ $item['question']['id'] }}">
                            <input type="hidden" name="ans_{{ $item['question']['id'] }}" id="ans_{{ $item['question']['id'] }}">
                            @foreach ($item['question']['answers'] as $answer )
                                <p>
                                    <b>{{ $loop->iteration }}).</b>{{ $answer['answer']}}
                                    <input type="radio" name="radio_{{ $item['question']['id'] }}" value="{{ $answer['id'] }}" data-id="{{ $item['question']['id'] }}" class="select_ans">
                                </p>
                            @endforeach
                        </div>
                        <br>
                    @endforeach
                    <div class="text-center">
                        <input type="submit" class="btn btn-info">
                    </div>
                </form>
            @else
                <h3 class="text-center" style="color:red">Questions & Answers not available</h3>
            @endif
        @else
            <h3 class="text-center" style="color:red">{{ $msg }}</h3>
        @endif
    </div>
    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $('.select_ans:checked').each(function() {
                    var no = $(this).attr('data-id');
                    $('#ans_' + no).val($(this).val());
                });
            });

            var time = @json($time);
            $('.time').text(time[0]+':'+time[1]+':00 Left Time');

            var seconds = 60;
            var hours = time[0];
            var minutes = time[1];

            setInterval(() => {
                if (seconds <= 0) {
                    minutes--;
                    seconds = 60;
                }
                if (minutes <= 0) {
                    hours--;
                    minutes = 59;
                    seconds = 60;
                }

                let tempHours = hours.toString().length > 1? hours:'0'+hours;
                let tempMinutes = minutes.toString().length > 1? minutes:'0'+minutes;
                let tempSeconds = seconds.toString().length > 1? seconds:'0'+seconds;

                $('.time').text(tempHours+':'+tempMinutes+':'+tempSeconds+' Left Time');
                seconds--;
            }, 1000);
        });

        function isValid() {
            var result = true;
            var qlength = parseInt("{{ $qcount }}") - 1;

            for (let i=1; i<=qlength; i++) {
                if ($('#ans_' + i).val() == "") {
                    result = false;
                    $('#ans_'+i).parent().append('<span class="error_msg" style="color:red;">Please select answer for question ' + i + '</span>');
                    setTimeout(function () {
                        $('.error_msg').remove();
                    },5000);
                }
            }
            return result;
        }

    </script>
@endsection
