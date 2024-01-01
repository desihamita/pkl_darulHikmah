@extends('layout.layout-common')

@section('space-work')
    <div class="container">
        <p style="color:black">welcome, {{ Auth::user()->name }}</p>
        <h1 class="text-center">{{ $exam[0]['exam_name'] }}</h1>

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
