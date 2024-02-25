@extends('layouts.main')

@section('title', 'Kelas')
@section('titleContent', 'Kelas')

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
                    <form action="{{ route('classDashboard')}}" method="GET">
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
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($kelas) > 0)
                        @foreach ($kelas as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->class }}</td>
                                <td>{{ $item->semester }}</td>
                                <td>
                                    <button class="btn btn-primary updateButton" data-id="{{ $item->id }}" data-kelas="{{ $item->class }}" data-semester="{{ $item->semester }}" data-toggle="modal" data-target="#modal-update">Update</button>

                                    <button class="btn btn-danger deleteButton" data-id="{{ $item->id }}" data-kelas="{{ $item->class }}" data-semester="{{ $item->semester }}" data-toggle="modal" data-target="#modal-delete">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">Kelas Tidak Ditemukan!</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Semester</th>
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
            <h4 class="modal-title">Tambah Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" id="createClass">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <input type="text" name="kelas" placeholder="Enter Kelas" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select name="semester" class="form-control" required>
                        <option value="">Select Semester</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
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

    {{-- Modal update --}}
    <div class="modal fade" id="modal-update">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ubah Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="updateClass">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="update_kelas_id">
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input type="text" name="kelas" placeholder="Enter Class" id="update_kelas" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select name="semester" id="update_semester" class="form-control" required>
                            <option value="">Select Semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
          </div>
        </div>
    </div>

    {{-- Modal delete --}}
    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Hapus Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="deleteClass">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete class?</p>
                        <input type="hidden" name="id" id="delete_kelas_id">
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
        $('#createClass').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('createClass') }}",
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
            var kelas_id = $(this).attr('data-id');
            var kelas = $(this).attr('data-kelas');
            var semester = $(this).attr('data-semester');

            $("#update_kelas").val(kelas);
            $("#update_semester").val(semester);
            $("#update_kelas_id").val(kelas_id);
        });

        $('#updateClass').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('updateClass') }}",
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
            var kelas_id = $(this).attr('data-id');
            $("#delete_kelas_id").val(kelas_id);
        });

        $('#deleteClass').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('deleteClass') }}",
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
