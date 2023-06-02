@extends('layout/admin-layout')
@section('space-work')

<h2 class="mb-4">Marks</h2>

<table class="table">
    <thead>
        <th>#</th>
        <th>Exam Name</th>
        <th>Marks per Question</th>
        <th>Total marks</th>
        <th>Edit</th>
    </thead>
    <tbody>
        @if(count($exams) > 0)
        @php $x = 1; @endphp
            @foreach($exams as $exam)
                <tr>
                    <td>{{ $x++ }} </td>
                    <td>{{ $exam->exam_name }} </td>
                    <td>{{ $exam->marks }} </td>
                    <td>{{ count($exam->getQnaExam ) * $exam->marks}} </td>


                </tr>
            @endforeach
        @else
        <tr>
            <td colspan="5">Exams not Added!</td>
        </tr>
        @endif

    </tbody>
</table>

@endsection
