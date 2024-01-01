@extends('layout.student-layout')

@section('space-work')
    <h1>Exams</h1>
    <div class="row">
        @foreach ($exams as $item)
        <div class="col-sm-6 mb-3 mb-sm-0">
          <div class="card">
            <div class="card-header bg-primary"></div>
            <div class="card-body">
                <h5 class="card-title">{{ $item->exam_name }}</h5>
                <form method="post" action="{{ route('check.token') }}" >
                    @csrf
                    <div class="card-text">
                        <label style="display:none;">{{ $item->id }}</label>
                        <div class="row">
                          <label class="col-sm-4 col-form-label">Mata Pelajaran </label>
                          <div class="col-sm-8">
                            <label class="col-form-label">:&nbsp; {{ $item->subjects->subject}}</label>
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-sm-4 col-form-label">Tanggal Ujian</label>
                          <div class="col-sm-8">
                            <label class="col-form-label">:&nbsp; {{ $item->date}}</label>
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-sm-4 col-form-label">Waktu Ujian</label>
                          <div class="col-sm-8">
                            <label class="col-form-label">:&nbsp; {{ $item->time}}</label>
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-sm-4 col-form-label">Total Attempt</label>
                          <div class="col-sm-8">
                            <label class="col-form-label">:&nbsp; {{ $item->attempt}}</label>
                          </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label">Available Attempt</label>
                            <div class="col-sm-8">
                                <label class="col-form-label">:&nbsp; {{ $item->attempt_counter }}</label>
                            </div>
                        </div>                        
                        <div class="row">
                          <label class="col-sm-4 col-form-label">Token</label>
                          <div class="col-sm-8">
                            :&nbsp;<input type="text" name="token" id="token">
                          </div>
                        </div>
                    </div>
                    <div class="d-grid mt-3 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary" type="submit">Mulai</button>
                    </div>
                </form>
                <div id="notif">
                    @if(session('message'))
                        {{ session('message') }}
                    @endif
                </div>
            </div>
          </div>
        </div>
        @endforeach
    </div>
@endsection

