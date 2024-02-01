@extends('layouts.main')

@section('title', 'Hasil Ujian')
@section('titleContent', 'Hasil Ujian')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline">
                    <div class="card-header">
                        <h3 class="card-title mt-2">
                            <a href="{{ route('exportPdf', ['search' => $request->get('search')]) }}" class="btn btn-success">
                                Export Data
                            </a>
                        </h3>

                        <div class="card-tools">
                            <div class="input-group mt-2">
                                <form action="{{ route('reviewExams')}}" method="GET">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control float-right"
                                            placeholder="Search" value="{{ $request->get('search') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Mata Pelajaran</th>
                                    <th>KKM</th>
                                    <th>Benar</th>
                                    <th>Salah</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($attemps) > 0)
                                    @foreach ($attemps as $attempt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attempt->user->name }}</td>
                                        <td>{{ $attempt->exam->subjects->subject }}</td>
                                        <td>{{ $attempt->exam->pass_marks }}</td>
                                        <td class="total-correct-answers" data-attempt-id="{{ $attempt->id }}"></td>
                                        <td class="total-incorrect-answers" data-attempt-id="{{ $attempt->id }}"></td>
                                        <td class="total-score" data-attempt-id="{{ $attempt->id }}"></td>
                                        <td>
                                            @if ($attempt->status == 0)
                                                <span style="color:red">Tidak Lulus</span>
                                                <a href="" data-id="{{ $attempt->id }}" data-toggle="modal" data-target="#reviewExamModal" class="reviewExam" style="display:none">Review & Approved</a>
                                            @else
                                                <span style="color: green">Lulus</span>
                                                <a href="" data-id="{{ $attempt->id }}" data-toggle="modal" data-target="#reviewExamModal" class="reviewExam" style="display:none">Review & Approved</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($attempt->status == 0)
                                                <a href="" data-id="{{ $attempt->id }}" data-toggle="modal"
                                                    data-target="#reviewExamModal" class="reviewExam">Review & Approved</a>
                                            @else
                                                Completed
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="9" class="text-center">Students not attempt exams!</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Benar</th>
                                    <th>Salah</th>
                                    <th>KKM</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Review</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="reviewExamModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Review Exam </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="reviewForm">
                    @csrf
                    <input type="hidden" name="attempt_id" id="attempt_id">
                    <div class="modal-body review-exam">
                        loading...
                    </div>
                    <div class="modal-footer">
                        <span class="error" style="color:red;"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Approved</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<script>
    $(document).ready(function(){
        $('.reviewExam').each(function () {
            var attemptId = $(this).attr('data-id');
            fetchTotalCorrectAnswers(attemptId);
        });

        $('.reviewExam').click(function () {
            var id = $(this).attr('data-id');
            $('#attempt_id').val(id);

            $.ajax({
                url: "{{ route('reviewQna') }}",
                type: "GET",
                data: { attempt_id: id },
                success: function (data) {
                    var html = '';
                    var totalCorrectAnswers = 0;
                    var totalIncorrectAnswers = 0;

                    if (data.success == true) {
                        var data = data.msg;
                        if (data.length > 0) {
                            for (let i = 0; i < data.length; i++) {
                                let is_correct = `<span class="fas fa-times" style="color:red;"></span>`;

                                if (data[i]['answer']['is_correct'] == 1) {
                                    is_correct = `<span class="fa fa-check" style="color:green;"></span>`;
                                    totalCorrectAnswers++;
                                } else {
                                    totalIncorrectAnswers++;
                                }

                                let answer = data[i]['answer']['answer'];
                                html += `
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h6>Q(${i + 1}). ${data[i]['question']['question']}</h6>
                                            <p>Ans: ${answer} ${is_correct}</p>
                                        </div>
                                    </div>
                                `;
                            }
                        } else {
                            html += `
                                <h6>Siswa belum menjawab pertanyaan apapun!</h6>
                                <p>Jika Anda menyetujui ujian ini, siswa akan gagal!</p>
                            `;
                        }
                        $('.review-exam').html(html);

                        $('.total-correct-answers[data-attempt-id="' + id + '"]').text(totalCorrectAnswers);
                        $('.total-incorrect-answers[data-attempt-id="' + id + '"]').text(totalIncorrectAnswers);
                    }
                }
            });
        });

        $('#reviewForm').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('approvedQna') }}",
                type: "POST",
                data: formData,
                success: function (data) {
                    if (data.success == true) {
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        });

        function fetchTotalCorrectAnswers(attemptId) {
            $.ajax({
                url: "{{ route('reviewQna') }}",
                type: "GET",
                data: { attempt_id: attemptId },
                success: function (data) {
                    var html = '';
                    var totalCorrectAnswers = 0;
                    var totalIncorrectAnswers = 0;
                    var totalQuestions = 0;

                    if (data.success == true) {
                        var data = data.msg;
                        if (data.length > 0) {
                            totalQuestions = data.length;

                            for (let i = 0; i < totalQuestions; i++) {
                                if (data[i]['answer']['is_correct'] == 1) {
                                    totalCorrectAnswers++;
                                } else {
                                    totalIncorrectAnswers++;
                                }
                            }
                        }
                    }
                    $('.total-correct-answers[data-attempt-id="' + attemptId + '"]').text(totalCorrectAnswers);
                    $('.total-incorrect-answers[data-attempt-id="' + attemptId + '"]').text(totalIncorrectAnswers);

                    var totalScore = calculateTotalScore(totalCorrectAnswers, totalQuestions);
                    $('.total-score[data-attempt-id="' + attemptId + '"]').text(totalScore);
                }
            });
        }

        function calculateTotalScore(totalCorrect, totalQuestions) {
            var totalScore = (totalCorrect * 100) / totalQuestions;

            totalScore = Math.max(0, Math.min(totalScore));
            totalScore = Math.round(totalScore);

            return totalScore;
        }
    });
</script>
@endsection
