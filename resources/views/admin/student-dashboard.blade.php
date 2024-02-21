@extends('layouts.main')

@section('title', 'Siswa/I')
@section('titleContent', 'Siwa/I')

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline">
            <div class="card-header">
              <h3 class="card-title mt-2">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-create">
                    Tambah Data
                  </button>
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-import">
                    Import Data
                </button>
              </h3>

              <div class="card-tools">
                  <div class="input-group mt-2" >
                    <form action="{{ route('studentDashboard')}}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ $request->get('search') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success">
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
                        <th>No Peserta</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>kelas</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($students) > 0)
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->no_peserta }}</td>
                                <td>{{ $student->nis }}</td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    @if ($student->kelas)
                                        {{ $student->kelas->class }}
                                    @else
                                        No Class Assigned
                                    @endif
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <button class="btn btn-success updateButton" data-id="{{ $student->id }}" data-student="{{ $student->name }}" data-email="{{ $student->email }}" data-no-peserta="{{ $student->no_peserta }}" data-nis="{{ $student->nis }}" data-kelas="{{ $student->kelas_id }}" data-toggle="modal" data-target="#modal-update">Update</button>

                                    <button class="btn btn-danger deleteButton" data-id="{{ $student->id }}" data-student="{{ $student->name }}" data-kelas="{{ $student->kelas_id }}" data-toggle="modal" data-target="#modal-delete">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Siswa/I Tidak Ditemukan!</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Peserta</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>kelas</th>
                        <th>Email</th>
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
          <form id="createStudent">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="no_peserta">No Peserta</label>
                    <input type="text" class="form-control" name="no_peserta" placeholder="Enter No Peserta" required>
                </div>
                <div class="form-group">
                    <label for="NIS">NIS</label>
                    <input type="text" class="form-control" name="nis" placeholder="Enter NIS" required>
                </div>
                <div class="form-group">
                    <label for="Nama">Nama</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">Select Kelas</option>
                        @if (count($kelas) > 0)
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->class }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter Email" required>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save changes</button>
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
              <h4 class="modal-title">Ubah Data Siswa/i</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="updateStudent">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="no_peserta">No Peserta</label>
                        <input type="text" name="no_peserta" id="no_peserta"  placeholder="Enter No Peserta" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="NIS">NIS</label>
                        <input type="text" name="nis" id="nis"  placeholder="Enter NIS" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Nama">Nama</label>
                        <input type="text" name="nama" id="nama" placeholder="Enter Name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">Select Kelas</option>
                            @if (count($kelas) > 0)
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->class }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Your Email" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Edit</button>
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
              <h4 class="modal-title">Hapus Data Siswa/I</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="deleteStudent">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus siswa?</p>
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
    </div>

    {{-- Modal import --}}
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Import Data Siswa/I</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="importStudent" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="file" name="file" id="fileupload" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Import Qna</button>
                </div>
            </form>
          </div>
        </div>
    </div>

</section>
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
            var no_peserta = $(this).attr('data-no-peserta');
            var nis = $(this).attr('data-nis');
            var name = $(this).attr('data-student');
            var email = $(this).attr('data-email');
            var kelas_id = $(this).attr('data-kelas');

            $("#nis").val(nis);
            $("#no_peserta").val(no_peserta);
            $("#nama").val(name);
            $("#email").val(email);
            $("#kelas_id").val(kelas_id);
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
