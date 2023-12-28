@extends('layout.admin-layout')

@section('space-work')
    <h2 class="mb-4">Exams</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExamModal">
        Add Exam
    </button>

    {{-- Tables subject --}}
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Exam Name</th>
            <th scope="col">Subject</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
            <th scope="col">Attempt</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if (count($exams) > 0)
                @foreach ($exams as $exam)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $exam->exam_name }}</td>
                        <td>{{ $exam->subjects[0]['subject'] }}</td>
                        <td>{{ $exam->date }}</td>
                        <td>{{ $exam->time }} hrs</td>
                        <td>{{ $exam->attempt }} </td>
                        <td>
                            <button class="btn btn-info updateButton" data-id="{{ $exam->id }}" data-exam="{{ $exam->exam_name }}" data-toggle="modal" data-target="#updateExamModal">Update</button>

                            <button class="btn btn-danger deleteButton" data-id="{{ $exam->id }}" data-exam="{{ $exam->exam_name }}" data-toggle="modal" data-target="#deleteExamModal">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Subjects not found!</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Create Modal -->
    <div class="modal fade" id="addExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createExam">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="exam_name" placeholder="Enter Exam Name" class="w-100" required> <br><br>
                        <select name="subject_id" class="w-100" required>
                            <option value="">Select Subject</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
                        <br><br>
                        <input type="date" name="date" class="w-100" min="@php echo date('Y-m-d'); @endphp" required>
                        <br><br>
                        <input type="time" name="time" class="w-100" required>
                        <br><br>
                        <input type="number" name="attempt" class="w-100" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Exam --}}
    <div class="modal fade" id="updateExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateExam">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="exam_id" id="exam_id">
                        <input type="text" name="exam_name" id="exam_name" placeholder="Enter Exam Name" class="w-100" required> <br><br>
                        <select name="subject_id" id="exam_subject_id" class="w-100" required>
                            <option value="">Select Subject</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
                        <br><br>
                        <input type="date" name="date" class="w-100" min="@php echo date('Y-m-d'); @endphp" id="exam_date" required>
                        <br><br>
                        <input type="time" name="time" class="w-100" id="exam_time" required>
                        <br><br>
                        <input type="number" name="attempt" class="w-100" id="exam_attempt" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <form id="deleteExam">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete exam?</p>
                        <input type="hidden" name="exam_id" id="delete_exam_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#createExam').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('createExam') }}",
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

            // Update subject
            $('.updateButton').click(function() {
                var id = $(this).attr('data-id');
                $("#exam_id").val(id);

                var url = '{{ route("getExamDetail", "id") }}';
                url = url.replace('id', id);

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(data) {
                        if (data.success == true) {
                            var exam = data.data;
                            $("#exam_name").val(exam[0].exam_name);
                            $("#exam_subject_id").val(exam[0].subject_id);
                            $("#exam_time").val(exam[0].time);
                            $("#exam_date").val(exam[0].date);
                            $("#exam_attempt").val(exam[0].attempt);
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            $('#updateExam').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('updateExam') }}",
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

            // Delete Subject
            $(".deleteButton").click(function() {
                var id = $(this).attr('data-id');
                $("#delete_exam_id").val(id);
            });

            $('#deleteExam').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('deleteExam') }}",
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
