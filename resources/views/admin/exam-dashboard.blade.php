@extends('layouts.main')

@section('title', 'Ujian')
@section('titleContent', 'Ujian')

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
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-import">
                    Import Data
                </button>
              </h3>

              <div class="card-tools">
                  <div class="input-group mt-2" >
                    <form action="{{ route('examDashboard')}}" method="GET">
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
                        <th>Token</th>
                        <th>Nama Ujian</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tanggal Ujian</th>
                        <th>Waktu Ujian</th>
                        <th>Percobaan</th>
                        <th>Tambah Pertanyaan</th>
                        <th>Lihat Pertanyaan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($exams) > 0)
                        @foreach ($exams as $exam)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $exam->token }}</td>
                                <td>{{ $exam->exam_name }}</td>
                                <td>{{ $exam->subjects->subject }}</td>
                                <td>
                                    @if ($exam->kelas)
                                        {{ $exam->kelas->class }}
                                    @else
                                        No Class Assigned
                                    @endif
                                </td>
                                <td>{{ $exam->date }}</td>
                                <td>{{ $exam->time }} hrs</td>
                                <td>{{ $exam->attempt }} </td>
                                <td>
                                    <a href="" class="addQuestion" data-id="{{ $exam->id }}" data-toggle="modal" data-target="#addQnaModal">Add Question</a>
                                </td>
                                <td>
                                    <a href="" class="seeQuestion" data-id="{{ $exam->id }}" data-toggle="modal" data-target="#seeQnaModal">See Question</a>
                                </td>
                                <td id="statusBadge_{{ $exam->id }}">
                                    @if ($exam->status == true)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning updateStatusButton" data-id="{{ $exam->id }}" data-exam="{{ $exam->exam_name }}" data-status="{{ $exam->status }}" >Ubah Status</button>

                                    <button class="btn btn-info updateButton" data-id="{{ $exam->id }}" data-exam="{{ $exam->exam_name }}" data-toggle="modal" data-target="#modal-update">Update</button>

                                    <button class="btn btn-danger deleteButton" data-id="{{ $exam->id }}" data-exam="{{ $exam->exam_name }}" data-toggle="modal" data-target="#modal-delete">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">Ujian Tidak Ditemukan!</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>Nama Ujian</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tanggal Ujian</th>
                        <th>Waktu Ujian</th>
                        <th>Percobaan</th>
                        <th>Tambah Pertanyaan</th>
                        <th>Lihat Pertanyaan</th>
                        <th>Status</th>
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
            <form id="createExam">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exam_name">Nama Ujian</label>
                        <input type="text" name="exam_name" placeholder="Enter Exam Name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="mata_pelajaran">Mata Pelajaran</label>
                        <select name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
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
                        <label for="date">Tanggal Ujian</label>
                        <input type="date" name="date" class="form-control" min="@php echo date('Y-m-d'); @endphp" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Waktu Ujian</label>
                        <input type="time" name="time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="attempt">Jumlah Percobaan Ujian</label>
                        <input type="number" name="attempt" class="form-control" required>
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
            <form id="updateExam">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="exam_id" id="exam_id">
                    <div class="form-group">
                        <label for="exam_name">Nama Ujian</label>
                        <input type="text" name="exam_name" id="exam_name" placeholder="Enter Exam Name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Mata Pelajaran</label>
                        <select name="subject_id" id="exam_subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select name="kelas_id" id="exam_kelas_id" class="form-control" required>
                            <option value="">Select Kelas</option>
                            @if (count($kelas) > 0)
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->class }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal Ujian</label>
                        <input type="date" name="date" class="form-control" min="@php echo date('Y-m-d'); @endphp" id="exam_date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Waktu Ujian</label>
                        <input type="time" name="time" class="form-control" id="exam_time" required>
                    </div>
                    <div class="form-group">
                        <label for="attempt">Jumlah Percobaan</label>
                        <input type="number" name="attempt" class="form-control" id="exam_attempt" required>
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

    {{-- Modal create question --}}
    <div class="modal fade" id="addQnaModal" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Pertanyaan dan Jawaban</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addQna">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="exam_id" id="addExamId">

                        <div class="form-group">
                            <input type="search" name="search" id="search" class="form-control" onkeyup="searchTable()" class="w-100" placeholder="Search here">
                        </div>

                        <table class="table table-bordered table-striped" id="example2 questionsTable">
                            <thead>
                                <th>Pilih</th>
                                <th>Mata Pelajaran</th>
                                <th>Pertanyaan</th>
                            </thead>
                            <tbody class="addBody">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create question Modal -->
    <div class="modal fade" id="seeQnaModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Soal Ujian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <th>No</th>
                                <th>Soal</th>
                                <th>Delete</th>
                            </thead>
                            <tbody class="seeQuestionTable">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>

</section>

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
        $(document).on('click', '.updateButton', function() {
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
                        $("#exam_kelas_id").val(exam[0].kelas_id);
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
        $(document).on('click', '.deleteButton', function() {
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

        // add questions
        $(document).on('click', '.addQuestion', function() {
            var id = $(this).attr('data-id');
            $('#addExamId').val(id);

            $.ajax({
                url: "{{ route('getQuestions') }}",
                type: "GET",
                data: {exam_id: id},
                success:function(data) {
                    if (data.success == true) {
                        console.log(data)
                        var questions = data.data;
                        var html = '';
                        if (questions.length > 0) {
                            for(let i=0; i<questions.length; i++){
                                html +=`
                                    <tr>
                                        <td>
                                            <input type="checkbox" value="`+questions[i]['id']+`" name="questions_ids[]">
                                        </td>
                                        <td>
                                            `+questions[i]['subjects']+`
                                        </td>
                                        <td>
                                            `+questions[i]['questions']+`
                                        </td>
                                    </tr>
                                `;
                            }
                        } else {
                            html +=`
                                <tr>
                                    <td colspan='2'>Questions not available</td>
                                </tr>
                            `;
                        }
                        $('.addBody').html(html);
                    } else {
                        alert(data.msg);
                    }
                }
            })
        });
        $('#addQna').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('addQuestions') }}",
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

        // see Questions

        $(document).on('click', '.seeQuestion', function() {
            var id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('getExamQuestions') }}",
                type: "GET",
                data: {exam_id: id},
                success:function(data) {
                    var questions = data.data;
                    var html = '';
                    if (questions.length > 0) {
                        for(let i=0; i<questions.length; i++){
                            if (questions[i]['question'] !== null && questions[i]['question'] !== undefined) {
                                html +=`
                                    <tr>
                                        <td>`+(i+1)+`</td>
                                        <td>`+questions[i]['question']['question']+`</td>
                                        <td>
                                            <button class="btn btn-danger deleteQuestion" data-id="`+questions[i]['id']+`">Delete</button>
                                        </td>
                                    </tr>
                                `;
                            } else {
                                console.error('Question data is null or undefined for index:', i);
                            }

                        }
                    } else {
                        html +=`
                            <tr>
                                <td colspan='2'>Questions not available</td>
                            </tr>
                        `;
                    }
                    $('.seeQuestionTable').html(html);
                }
            })
        });

        // delete Question
        $(document).on('click', '.deleteQuestion', function(){
            var id = $(this).attr('data-id');
            var obj = $(this);
            $.ajax({
                url: "{{ route('deleteExamQuestions') }}",
                type: "GET",
                data: {id: id},
                success:function(data) {
                    if (data.success == true) {
                        obj.parent().parent().remove();
                    } else {
                        alert(data.msg);
                    }
                }
            })
        });

        // update status
        $(document).on('click', '.updateStatusButton', function() {
            var id = $(this).attr('data-id');

            $.ajax({
                url: "{{ url('update-status') }}/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.status == true) {
                        $('#statusBadge_' + id).html('<span class="badge badge-success">Aktif</span>');
                        location.reload();
                    } else if(data.status == false) {
                        $('#statusBadge_' + id).html('<span class="badge badge-danger">Tidak Aktif</span>');
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        });

    });
</script>
<script>
    function searchTable(){
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('search');
        filter = input.value.toUpperCase();
        table = document.getElementById('questionsTable');
        tr = table.getElementsByTagName("tr");

        for(i=0; i < tr.length; i++){
            td = tr[i].getElementsByTagName("td")[1];
            if(td){
                txtValue = td.textContent || td.innerText;
                if(txtValue.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection
