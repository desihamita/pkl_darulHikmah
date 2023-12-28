@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Students</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addStudentModal">
        Add Student
    </button>

    <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importStudentModal">
        Import QnA
    </button>

    {{-- Tables subject --}}
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">NIS</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if (count($students) > 0)
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <button class="btn btn-info updateButton" data-id="{{ $student->id }}" data-student="{{ $student->name }}" data-email="{{ $student->email }}" data-nis="{{ $student->nis }}" data-toggle="modal" data-target="#updateStudentModal">Update</button>

                            <button class="btn btn-danger deleteButton" data-id="{{ $student->id }}" data-student="{{ $student->name }}" data-toggle="modal" data-target="#deleteStudentModal">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">Subjects not found!</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Create Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createStudent">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="w-100" name="nis" placeholder="Enter Student NIS" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input type="text" class="w-100" name="name" placeholder="Enter Student Name" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input type="text" class="w-100" name="email" placeholder="Enter Student Email" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input type="password" class="w-100" name="password" placeholder="Enter Student Password" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input type="password" class="w-100" name="password_confirmation" placeholder="Enter Student Password" required>
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

    {{-- Edit Exam --}}
    <div class="modal fade" id="updateStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateStudent">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="text" name="nis" id="nis" placeholder="Enter Exam NIS" class="w-100" required> <br><br>
                        <input type="text" name="nama" id="nama" placeholder="Enter Exam Name" class="w-100" required> <br><br>
                        <input type="email" name="email" class="w-100"  id="email" placeholder="Enter Your Email" required>
                        <br><br>
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
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Qna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <form id="deleteStudent">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete Qna?</p>
                        <input type="hidden" name="id" id="delete_student_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Qna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <form id="importStudent" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" name="file" id="fileupload" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Import Qna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#createStudent').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('createStudent') }}",
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
                var nis = $(this).attr('data-nis');
                var name = $(this).attr('data-student');
                var email = $(this).attr('data-email');

                $("#nis").val(nis);
                $("#nama").val(name);
                $("#email").val(email);
                $("#id").val(id);
            });

            $('#updateStudent').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('updateStudent') }}",
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

            // delete Qna
            $('.deleteButton').click(function() {
                var id = $(this).attr('data-id');
                $('#delete_student_id').val(id)
            });

            $('#deleteStudent').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('deleteStudent') }}",
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

            // import Qna
            $('#importStudent').submit(function (e) {
                e.preventDefault();

                let formData = new FormData();

                formData.append("file", fileupload.files[0]);


                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN":"{{ csrf_token() }}",
                    }
                });

                $.ajax({
                    url: "{{ route('importStudent') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
