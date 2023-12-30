@extends('layout.layout-common')

@section('space-work')
    <div class="container">
        <p style="color:black">welcome, {{ Auth::user()->name }}</p>
        <h1 class="text-center">{{ $exam[0]['exam_name'] }}</h1>

        @if ($success == true)
            @if (count($qna) > 0)
                @foreach ($qna as $item)
                    <h5>Q{{ $loop->iteration }}. {{ $item['question']['question'] }}</h5>
                    @foreach ($item['question']['answers'] as $answer )
                        <p><b>{{ $loop->iteration }}).</b>{{ $answer['answer']}}</p>
                    @endforeach
                    <br>
                @endforeach
            @else
                <h3 class="text-center" style="color:red">Questions & Answers not available</h3>
            @endif
        @else
            <h3 class="text-center" style="color:red">{{ $msg }}</h3>
        @endif
    </div>
@endsection