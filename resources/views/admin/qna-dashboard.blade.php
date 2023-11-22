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
                            <button class="btn btn-info updateButton" data-id="{{ $question->id }}" data-question="{{ $question->question }}" data-toggle="modal" data-target="#updateSubjectModal">Update</button>

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
                    <button type="submit" class="btn btn-primary">Save </button>
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
                    <div class="modal-body">
                        <div class="row answers">
                            <div class="col">
                                <input type="text" class="w-100" name="question" placeholder="Enter Question" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="error" style="color:red;"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save </button>
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

                    $(".modal-body").append(html);

                }
            });

            $(document).on("click", ".removeButton", function() {
                $(this).closest('.answers').remove();
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
        })
    </script>
@endsection
