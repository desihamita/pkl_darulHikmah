@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Subject</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubjectModal">
        Add Subject
    </button>

    {{-- Tables subject --}}
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Subject</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if (count($subjects) > 0)
                @foreach ($subjects as $subject)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $subject->subject }}</td>
                        <td>
                            <button class="btn btn-info updateButton" data-id="{{ $subject->id }}" data-subject="{{ $subject->subject }}" data-toggle="modal" data-target="#updateSubjectModal">Update</button>

                            <button class="btn btn-danger deleteButton" data-id="{{ $subject->id }}" data-subject="{{ $subject->subject }}" data-toggle="modal" data-target="#deleteSubjectModal">Delete</button>
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
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="createSubject">
                    @csrf
                    <div class="modal-body">
                        <label for="">Subject:-</label>
                        <input type="text" name="subject" placeholder="Enter Subject Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- update Modal -->
    <div class="modal fade" id="updateSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <form id="updateSubject">
                    @csrf
                    <div class="modal-body">
                        <label for="">Subject:-</label>
                        <input type="text" name="subject" placeholder="Enter Subject Name" id="update_subject" required>
                        <input type="hidden" name="id" id="update_subject_id">
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
    <div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <form id="deleteSubject">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete subject?</p>
                        <input type="hidden" name="id" id="delete_subject_id">
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
        jQuery(document).ready(function () {
            $('#createSubject').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('createSubject') }}",
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
                var subject_id = $(this).attr('data-id');
                var subject = $(this).attr('data-subject');

                $("#update_subject").val(subject);
                $("#update_subject_id").val(subject_id);
            });

            $('#updateSubject').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('updateSubject') }}",
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
            $(".deleteButton").click(function (){
                var subject_id = $(this).attr('data-id');
                $("#delete_subject_id").val(subject_id);
            });

            $('#deleteSubject').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('deleteSubject') }}",
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
