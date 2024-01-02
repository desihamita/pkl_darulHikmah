@extends('layout/admin-layout')

@section('space-work')
    <h2 class="mb-4">Student Exam</h2>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Exam Name</th>
            <th scope="col">Status</th>
            <th scope="col">Review</th>
          </tr>
        </thead>
        <tbody>
            @if (count($attemps) > 0)
                @foreach ($attemps as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->exam->exam_name }}</td>
                        <td>
                            @if ($item->status == 0)
                                <span style="color:red">Pending</span>
                            @else
                                <span style="color: green">Approved</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->status == 0)
                                <a href="">Review & Approved</a>
                            @else
                                Completed
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Stuents not attempt exams!</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
