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
                @foreach ($attemps as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->exam->exam_name }}</td>
                        <td>
                            @if ($item->status == 0)
                                <span style="color:red">Pending</span>
                            @else
                                <span style="color: green">Approved</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->status == 0)
                                <a href="" data-id="{{ $item->id }}" data-toggle="modal" data-target="#reviewExamModal" class="reviewExam" >Review & Approved</a>
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

                $.ajax({
                    url: "{{ route('reviewQna') }}",
                    type: "GET",
                    data: {attempt_id: id},
                    success: function(response){
                        var html = '';
                        if (response.success == true) {
                            var responseData = response.msg;
                            if(Array.isArray(responseData) && responseData.length > 0){
                                for(let i=0; i<responseData.length; i++){
                                    let isCorrect = `<span style="color:red;" class="fa fa-close"></span>`;

                                    if (responseData[i]['answer']['answer'] == 1) {
                                        isCorrect = `<span style="color:green;" class="fa fa-check"></span>`;
                                    }

                                    let answer = responseData[i]['answer']['answer'];
                                    html += `
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Q(`+(i+1)+`). `+responseData[i]['question']['question']+`</h6>
                                                <p>Ans: `+answer+` `+isCorrect+`</p>
                                            </div>
                                        </div>
                                    `;
                                }
                            } else {
                                html += `
                                        <h6>Siswa belum menjawab pertanyaan apapun!</h6>` +
                                        `<p>Jika Anda menyetujui ujian ini, siswa akan gagal!</p>
                                `;
                            }
                        } else {
                            html += '<p>Having some server issue!</p>';
                        }
                        $('.review-exam').html(html);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log pesan kesalahan detail
                    }
                });
            });
        });

    </script>
@endsection
