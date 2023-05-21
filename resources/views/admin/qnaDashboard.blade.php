@extends('layout/admin-layout')
@section('space-work')

<h2 class="mb-4">Q & A</h2>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQnaModal">
    Add Q & A
</button>



<!-- Add Q & A Modal -->
<div class="modal fade" id="addQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Q & A</h5>

                    <button id="addAnswer" class="ml-5">Add Answer</button>




                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addQna">

            <div class="modal-body">
                <div class="row">
                <div class="col">
                    <input type="text" class="w-100" name="question" placeholder="Enter a Question" required>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="error" style="color:red;"></span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Q & A</button>
            </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        //form submition
        $("#addQna").submit(function(e){
            e.preventDefault();

            if($(".answers").length < 2){
                $(".error").text("Please add a minimum of two answers!")
                setTimeout(function(){
                    $(".error").text("");
                },2000);

            }
            else{

            }
        });

        //add Answers
        $("#addAnswer").click(function(){
            if($(".answers").length >=6){
                $(".error").text("You can add maximum six answers!")
                setTimeout(function(){
                    $(".error").text("");
                },2000);

            }

            else{
                var html =`
                    <div class="row mt-2 answers">
                    <input type="radio" name="is_correct" class="is_correct">
                        <div class="col">
                            <input type="text" class="w-100" name="answers[]" placeholder="Enter Answer" required>
                        </div>
                        <button class="btn btn-danger removeButton">Remove</button>
                    </div>
                    `;

                    $(".modal-body").append(html);
            }

        });

        $(document).on("click",".removeButton",function(){
            $(this).parent().remove();
        });
    });
</script>
@endsection