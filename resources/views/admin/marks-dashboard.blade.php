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
                            <div class="col-sm-3">
                                <label for="marksInput">Marks/Q</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="hidden" name="exam_id" id="exam_id">
                                <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46" name="marks" id="marksInput" placeholder="Enter Mark/Q" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="tmarksInput">Total Marks</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" placeholder="Total Marks" name="tmarks" id="tmarksInput" disabled>
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
                $('#marksInput').val(marks);
                $('#tmarksInput').val(marks * totalq);

                totalQna = totalq;
            });

            $('#marksInput').keyup(function () {
                $('#tmarksInput').val(($(this).val() * totalQna).toFixed(1));
            });

            $('#editMarks').submit(function (event) {
                event.preventDefault();

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
