@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Student Exam</h2>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Exam Name</th>
            <th scope="col">Status</th>
            <th scope="col">Review</th>
          </tr>
        </thead>
        <tbody>
            @if (count($attemps) > 0)
                @foreach ($attemps as $attempt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attempt->user->name }}</td>
                        <td>{{ $attempt->exam->exam_name }}</td>
                        <td>
                            @if ($attempt->status == 0)
                                <span style="color:red">Pending</span>
                            @else
                                <span style="color: green">Approved</span>
                            @endif
                        </td>
                        <td>
                            @if ($attempt->status == 0)
                                <a href="" data-id="{{ $attempt->id }}" data-toggle="modal" data-target="#reviewExamModal" class="reviewExam" >Review & Approved</a>
                            @else
                                Completed
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Students not attempt exams!</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Modal --}}
    <div class="modal fade" id="reviewExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
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
    <script>
        $(document).ready(function(){
            $('.reviewExam').click(function(){
                var id = $(this).attr('data-id');
                $('#attempt_id').val(id);

                $.ajax({
                    url: "{{ route('reviewQna') }}",
                    type: "GET",
                    data: {attempt_id: id},
                    success: function(data){
                        var html = '';
                        if (data.success == true) {
                            var data = data.msg;
                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    let is_correct = `<span class="fa fa-close" style="color:red;"></span>`;

                                    if (data[i]['answer']['is_correct'] == 1) {
                                        is_correct = `<span class="fa fa-check" style="color:green;"></span>`;
                                    }

                                    let answer = data[i]['answer']['answer'];
                                    html += `
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Q(${i+1}). ${data[i]['question']['question']}</h6>
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


        });
    </script>
@endsection
