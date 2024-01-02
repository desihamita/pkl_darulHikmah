@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Marks</h2>

    {{-- Tables subject --}}
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Exam Name</th>
            <th scope="col">Statistics</th>
            <th scope="col">Total Exam</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if (count($exams) > 0)
                @foreach ($exams as $item)
                    @php
                        $totalQuestions = count($item->qnaExams);
                        $correctAnswers = 0;
                        $incorrectAnswers = 0;
                        $unansweredQuestions = $totalQuestions;
                    @endphp

                    @foreach ($item->qnaExams as $qna)
                        @if ($qna->user_answer == $qna->correct_answer)
                            @php $correctAnswers++; $unansweredQuestions--; @endphp
                        @elseif ($qna->user_answer !== null)
                            @php $incorrectAnswers++; $unansweredQuestions--; @endphp
                        @endif
                    @endforeach

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->exam_name }}</td>
                        <td>
                            Correct: {{ $correctAnswers }}, <br>
                            Incorrect: {{ $incorrectAnswers }}, <br>
                            Unanswered: {{ $unansweredQuestions }}
                        </td>
                        <td>{{ $correctAnswers * $item->marks }}</td>
                        <td>
                            <button class="btn btn-info updateButton" data-toggle="modal" data-target="#updateStudentModal">Edit</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Exams not added!</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
