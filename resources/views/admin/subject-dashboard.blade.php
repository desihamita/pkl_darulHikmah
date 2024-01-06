@extends('layouts.main')

@section('title', 'Mata Pelajaran')
@section('titleContent', 'Mata Pelajaran')

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline">
            <div class="card-header">
              <h3 class="card-title mt-2">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
                      Tambah Data
                  </button>
              </h3>

              <div class="card-tools">
                  <div class="input-group mt-2" >
                    <form action="{{ route('subjectDashboard')}}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ $request->get('search') }}">

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
                        <th>Mata Pelajaran</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($subjects) > 0)
                        @foreach ($subjects as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->subject }}</td>
                                <td>
                                    <button class="btn btn-info updateButton" data-id="{{ $item->id }}" data-subject="{{ $item->subject }}" data-toggle="modal" data-target="#modal-update">Update</button>

                                    <button class="btn btn-danger deleteButton" data-id="{{ $item->id }}" data-subject="{{ $item->subject }}" data-toggle="modal" data-target="#modal-delete">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">Mata Pelajaran Tidak Ditemukan!</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade" id="modal-create">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Tambah Data Mata Pelajaran</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" id="createSubject">
                @csrf
                <div class="modal-body">
                    <label for="">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Enter Nama Mata Pelajaran" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal update --}}
    <div class="modal fade" id="modal-update">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ubah Data Mata Pelajaran</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="updateSubject">
                    @csrf
                    <div class="modal-body">
                        <label for="">Mata Pelajaran</label>
                        <input type="text" name="subject" placeholder="Enter Subject Name" id="update_subject" required class="form-control">
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
    </div>

    {{-- Modal delete --}}
    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Hapus Data Mata Pelajaran</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
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
    </div>

</section>
<script>
    $(document).ready(function () {
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
