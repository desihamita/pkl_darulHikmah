@extends('layouts.main')

@section('title', 'Soal')
@section('titleContent', 'Soal')

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
                    <form action="{{ route('qnaDashboard')}}" method="GET">
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
                        <th>Mata Pelajaran</th>
                        <th>kelas</th>
                        <th>Pertanyaan</th>
                        <th>Jawaban</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($questions) > 0)
                        @foreach ($questions as $question)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $question->subjects->subject }}</td>
                                <td>
                                    @if ($question->kelas)
                                        {{ $question->kelas->class }}
                                    @else
                                        No Class Assigned
                                    @endif
                                </td>
                                <td>{{ $question->question }}</td>
                                <td>
                                    <a href="#" class="ansButton" data-id="{{ $question->id }}"  data-toggle="modal" data-target="#showAnsModal">See Answers</a>
                                </td>
                                <td>
                                    <button class="btn btn-success updateButton" data-id="{{ $question->id }}" data-question="{{ $question->question }}" data-toggle="modal" data-target="#modal-update">Update</button>

                                    <button class="btn btn-danger deleteButton" data-id="{{ $question->id }}" data-question="{{ $question->question }}" data-toggle="modal" data-target="#modal-delete">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Pertanyaan dan Jawaban tidak ditemukan!</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Pertanyaan</th>
                        <th>kelas</th>
                        <th>Jawaban</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- show ans modal --}}
    <div class="modal fade" id="showAnsModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Show Answers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jawaban</th>
                                <th>Jawaban Benar</th>
                            </tr>
                        </thead>
                        <tbody class="showAnswers">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            <button id="createAnswer" class="btn btn-success ml-5">Add Answer</button>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="createQna">
            @csrf
            <div class="modal-body createModalAnswers">
                <div class="form-group">
                    <label for="question">Pertanyaan</label>
                    <input type="text" class="form-control" name="question" placeholder="Enter Question" required>
                </div>
                <div class="form-group">
                    <label for="subject">Mata Pelajaran</label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">Pilih Mata Pelajaran</option>
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
                        <option value="">Pilih Kelas</option>
                        @if (count($kelas) > 0)
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->class }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="explanation">Pembahasan (Optional)</label>
                    <textarea name="explanation" class="form-control" rows="5" placeholder="Enter your explanation (Optional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <span class="error" style="color:red;"></span>
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
              <h4 class="modal-title">Ubah Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="updateQna">
                @csrf
                <div class="modal-body updateModalAnswers">
                    <input type="hidden" name="question_id" id="question_id">
                    <div class="form-group">
                        <label for="question">Pertanyaan</label>
                        <input type="text" class="form-control" name="question" id="question" placeholder="Enter Question" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id" class="form-control" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            @if (count($kelas) > 0)
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->class }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="explanation">Pembahasan (Optional)</label>
                        <textarea name="explanation" id="explanation" class="form-control" placeholder="Enter your explanation (Optional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="updateError" style="color:red;"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Edit</button>
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
            <form id="deleteQna">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to delete Qna?</p>
                    <input type="hidden" name="id" id="delete_qna_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
          </div>
        </div>
    </div>

    {{-- Modal import --}}
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Import Soal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="importQna" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="file" name="file" class="form-control" id="fileupload" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Import Soal</button>
                </div>
             </form>
          </div>
        </div>
    </div>

</section>
<script>
    $(document).ready(function(){
        $("#createQna").submit(function(e){
            e.preventDefault();

            if ($(".answers").length < 2) {
                $(".error").text("Please add minimum two answers.")
                setTimeout(() => {
                    $(".error").text("");
                }, 2000);
            } else {
                var checkIsCorrect = false;
                for (let i = 0; i < $(".is_correct").length; i++) {
                    if ($(".is_correct:eq("+ i +")").prop('checked') == true) {
                        checkIsCorrect = true;
                        $(".is_correct:eq("+ i +")").val($(".is_correct:eq("+ i +")").next().find('input').val());
                    }
                }
                if (checkIsCorrect) {
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('createQna')}}",
                        type: "POST",
                        data: formData,
                        success: (data) => {
                            if (data.success == true) {
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        }
                    })
                } else {
                    $(".error").text("Please select anyone correct answer.")
                    setTimeout(() => {
                        $(".error").text("");
                    }, 2000);
                }
            }
        });

        $("#createAnswer").click(() => {
            if ($(".answers").length >= 6) {
                $(".error").text("You can add Maximum 6 answers.")
                setTimeout(() => {
                    $(".error").text("");
                }, 2000);
            } else {
                var html = `
                        <div class="row answers">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="radio" name="is_correct" class="form-check-input is_correct">
                                        <div class="col d-flex">
                                            <input type="text" class="form-control mr-2" name="answers[]" placeholder="Enter Answer" required>
                                            <button class="btn btn-danger removeButton">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;

                $(".createModalAnswers").append(html);

            }
        });

        // remove button
        $(document).on("click", ".removeButton", function() {
            var parentDiv = $(this).closest('.answers, .editAnswers');
            parentDiv.find('.is_correct, .edit_is_correct').remove();
            parentDiv.remove();
        });

        // show answer
        $(".ansButton").click(function() {
            var questions = @json($questions);
            var qid = $(this).attr('data-id');
            var html = '';

            for (let i = 0; i < questions.length; i++) {
                if (questions[i]['id'] == qid) {
                    var answerLength = questions[i]['answers'].length;
                    for (let j = 0; j < answerLength; j++) {
                        let is_correct = 'No';

                        if (questions[i]['answers'][j]['is_correct'] == 1) {
                            is_correct = 'Yes';
                        }

                        html += `
                            <tr>
                                <td>`+ (j+1) +`</td>
                                <td>`+ questions[i]['answers'][j]['answer'] +`</td>
                                <td>`+ is_correct +`</td>
                            </tr>
                        `;
                    }
                    break;
                }
            }
            $('.showAnswers').html(html);
        });

        // update qna
        $("#updateAnswer").click(() => {
            if ($(".editAnswers").length >= 6) {
                $(".updateError").text("You can add Maximum 6 answers.")
                setTimeout(() => {
                    $(".updateError").text("");
                }, 2000);
            } else {
                var html = `
                        <div class="row answers">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="radio" name="is_correct" class="form-check-input edit_is_correct">
                                        <div class="col d-flex">
                                            <input type="text" class="form-control mr-2" name="new_answers[]" placeholder="Enter Answer" required>
                                            <button class="btn btn-danger removeButton">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;

                $(".updateModalAnswers").append(html);
            }
        });

        $(".updateButton").click(function() {
            var question_id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('getQnaDetails') }}",
                type: "GET",
                data: { question_id: question_id },
                success: function(data) {
                    console.log(data);

                    var qna = data.data[0];
                    $("#question_id").val(qna['id']);
                    $("#question").val(qna['question']);
                    $("#subject_id").val(qna['subject_id']);
                    $("#kelas_id").val(qna['kelas_id']);
                    $("#explanation").val(qna['explanation']);
                    $(".editAnswers").remove();

                    var html = '';

                    for (let i = 0; i < qna['answers'].length; i++) {

                        var checked = '';
                        if (qna['answers'][i]['is_correct'] == 1) {
                            checked = 'checked';
                        }
                        html += `
                        <div class="row editAnswers">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="radio" name="is_correct" class="form-check-input edit_is_correct" `+ checked +`>
                                        <div class="col d-flex">
                                            <input type="text" class="form-control mr-2" name="answers[`+ qna['answers'][i]['id'] +`]" value="`+ qna['answers'][i]['answer'] +`" placeholder="Enter Answer" required>
                                            <button class="btn btn-danger removeButton removeAnswer" data-id="`+ qna['answers'][i]['id'] +`">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    }
                    $(".updateModalAnswers").append(html);
                }
            });
        });

        // update qna
        $("#updateQna").submit(function(e){
            e.preventDefault();

            if ($(".editAnswers").length < 2) {
                $(".updateError").text("Please add minimum two answers.")
                setTimeout(() => {
                    $(".updateError").text("");
                }, 2000);
            } else {
                var checkIsCorrect = false;
                for (let i = 0; i < $(".edit_is_correct").length; i++) {
                    if ($(".edit_is_correct:eq("+ i +")").prop('checked') == true) {
                        checkIsCorrect = true;
                        $(".edit_is_correct:eq("+ i +")").val($(".edit_is_correct:eq("+ i +")").next().find('input').val());
                    }
                }
                if (checkIsCorrect) {
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('updateQna') }}",
                        type: "POST",
                        data: formData,
                        success: function(data){
                            if (data.success == true) {
                                location.reload();
                            } else {
                                alert(data.msg);
                            }
                        }
                    });
                } else {
                    $(".updateError").text("Please select anyone correct answer.")
                    setTimeout(() => {
                        $(".updateError").text("");
                    }, 2000);
                }
            }
        });

        //remove answer
        $(document).on('click', '.removeAnswer', function(){
            var ansId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('deleteAns') }}",
                data: { id: ansId},
                success: function(data){
                   if (data.success == true) {
                        console.log(data.msg);
                   } else {
                        alert(data.msg);
                   }
                }
            });
        });

        // delete Qna
        $('.deleteButton').click(function() {
            var id = $(this).attr('data-id');
            $('#delete_qna_id').val(id)
        });

        $('#deleteQna').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('deleteQna') }}",
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
        $('#importQna').submit(function (e) {
            e.preventDefault();

            let formData = new FormData();

            formData.append("file", fileupload.files[0]);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN":"{{ csrf_token() }}",
                }
            });

            $.ajax({
                url: "{{ route('importQna') }}",
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
                }
            });
        });
    });
</script>
@endsection
