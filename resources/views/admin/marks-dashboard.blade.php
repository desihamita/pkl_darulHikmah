@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Marks</h2>

    {{-- Tables subject --}}
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Exam Name</th>
            <th scope="col">Marks</th>
            <th scope="col">Total Marks</th>
            <th scope="col">Passing Marks</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if (count($exams) > 0)
                @foreach ($exams as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->exam_name }}</td>
                        <td>{{ $item->marks }}</td>
                        <td>{{ count($item->qnaExams) * $item->marks }}</td>
                        <td>{{ $item->pass_marks }}</td>
                        <td>
                            <button class="btn btn-info editMarks" data-id="{{$item->id}}" data-marks="{{ $item->marks }}" data-totalq="{{count($item->qnaExams)}}" data-toggle="modal" data-target="#updateMarksModal">Edit</button>
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

    {{-- Modal --}}
    <div class="modal fade" id="updateMarksModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Marks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editMarks">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="marksInput">Marks/Q</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="hidden" name="exam_id" id="exam_id">
                                <input type="number" step="0.01" name="marks" id="marks" placeholder="Enter Mark/Q" required>

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-4">
                                <label for="tmarks">Total Marks</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" placeholder="Total Marks" name="tmarks" id="tmarks" disabled>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-4">
                                <label for="pmarks">Passing Marks</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" step="0.01" placeholder="Total Passing Marks" name="pass_marks" id="pass_marks" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var totalQna;

            $('.editMarks').click(function () {
                var exam_id = $(this).data('id');
                var marks = $(this).data('marks');
                var totalq = $(this).data('totalq');

                $('#exam_id').val(exam_id);
                $('#marks').val(marks);
                $('#tmarks').val(marks * totalq);

                totalQna = totalq;

                $('#pass_marks').val($(this).attr('data-pass-marks'))
            });

            $('#marks').keyup(function () {
                $('#tmarks').val(($(this).val() * totalQna).toFixed(1));
            });

            $('#pass_marks').keyup(function(){
                $('.pass-error').remove();

                var tmarks = $('#tmarks').val();
                var pmarks = $(this).val();

                if (parseFloat(pmarks) >= parseFloat(tmarks)) {
                    $(this).parent().append('<p style="color:red;" class="pass-error">Passing Marks will be less than total marks!</p>');
                    setTimeout(() => {
                        $('.pass-error').remove();
                    }, 2000);
                }
            });

            $('#editMarks').submit(function (event) {
                event.preventDefault();

                $('.pass-error').remove();
                var tmarks = $('#tmarks').val();
                var pmarks = $('#pass_marks').val();

                if (parseFloat(pmarks) >= parseFloat(tmarks)) {
                    $('#pass_marks').parent().append('<p style="color:red;" class="pass-error">Passing Marks will be less than total marks!</p>');
                    setTimeout(() => {
                        $('.pass-error').remove();
                    }, 2000);

                    return false;
                }

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('updateMarks') }}",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                        alert('An error occurred while processing your request.');
                    }
                });
            });
        });

    </script>
@endsection
