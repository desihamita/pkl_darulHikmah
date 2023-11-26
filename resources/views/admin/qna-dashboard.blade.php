@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Question and Answer</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQnaModal">
        Add QnA
    </button>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Question</th>
            <th scope="col">Answer</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if (count($questions) > 0)
                @foreach ($questions as $question)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $question->question }}</td>
                        <td>
                            <button class="btn btn-info ansButton" data-id="{{ $question->id }}"  data-toggle="modal" data-target="#showAnsModal">See Answers</button>
                        </td>
                        <td>
                            <button class="btn btn-info updateButton" data-id="{{ $question->id }}" data-question="{{ $question->question }}" data-toggle="modal" data-target="#updateQnaModal">Update</button>

                            <button class="btn btn-danger deleteButton" data-id="{{ $question->id }}" data-question="{{ $question->question }}" data-toggle="modal" data-target="#deleteSubjectModal">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">Questions and Answers not found!</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- show answer modal --}}
    <div class="modal fade" id="showAnsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
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
                                <th>Answer</th>
                                <th>Is Correct</th>
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

    {{-- modal create Qna --}}
    <div class="modal fade" id="addQnaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Qna</h5>
                    <button id="createAnswer" class="btn btn-info ml-5">Add Answer</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createQna">
                    @csrf
                    <div class="modal-body createModalAnswers">
                        <div class="row answers">
                            <div class="col">
                                <input type="text" class="w-100" name="question" placeholder="Enter Question" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="error" style="color:red;"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal update Qna --}}
    <div class="modal fade" id="updateQnaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Qna</h5>
                    <button id="updateAnswer" class="btn btn-info ml-5">Create Answer</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateQna">
                    @csrf
                    <div class="modal-body updateModalAnswers">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="question_id" id="question_id">
                                <input type="text" class="w-100" name="question" id="question" placeholder="Enter Question" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="updateError" style="color:red;"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <div class="row mt-2 answers">
                            <input type="radio" name="is_correct" class="is_correct">
                            <div class="col">
                                <input type="text" class="w-100" name="answers[]" placeholder="Enter Answer" required>
                                <button class="btn btn-danger removeButton">Remove</button>
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
                        <div class="row mt-2 editAnswers">
                            <input type="radio" name="is_correct" class="edit_is_correct">
                            <div class="col">
                                <input type="text" class="w-100" name="new_answers[]" placeholder="Enter Answer" required>
                                <button class="btn btn-danger removeButton">Remove</button>
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
                        $(".editAnswers").remove();

                        var html = '';

                        for (let i = 0; i < qna['answers'].length; i++) {

                            var checked = '';
                            if (qna['answers'][i]['is_correct'] == 1) {
                                checked = 'checked';
                            }
                            html += `
                                <div class="row mt-2 editAnswers">
                                    <input type="radio" name="is_correct" class="edit_is_correct" `+ checked +`>
                                    <div class="col">
                                        <input type="text" class="w-100" name="answers[`+ qna['answers'][i]['id'] +`]" value="`+ qna['answers'][i]['answer'] +`" placeholder="Enter Answer" required>
                                        <button class="btn btn-danger removeButton removeAnswer" data-id="`+ qna['answers'][i]['id'] +`">Remove</button>
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
        });
    </script>
@endsection
