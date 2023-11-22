@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Question and Answer</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQnaModal">
        Add QnA
    </button>

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
            })
        })
    </script>
@endsection
