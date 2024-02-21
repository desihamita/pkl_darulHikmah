@extends('layouts.student-layout')

@section('content')
    <div class="content-header">
        <div class="content-header"></div>
        <div class="container">
            <div class="row">
                @php
                    $activeExams = $exams->where('status', true);
                    $userClassId = Auth::user()->kelas->class;
                    $foundActiveExams = false;
                @endphp

                @foreach ($exams as $item)
                    @if ($item->status == true && $item->kelas->class == $userClassId)
                        <div class="col-lg-5">
                            <div class="card card-success card-outline">
                                <div class="card-body">
                                    <form method="post" action="{{ route('check.token') }}" >
                                        @csrf
                                        <dl class="row">
                                            <dt class="col-sm-4">No Peserta </dt>
                                            <dd class="col-sm-8">: {{ Auth::user()->no_peserta }}</dd>

                                            <dt class="col-sm-4">Nama Peserta </dt>
                                            <dd class="col-sm-8">: {{ Auth::user()->name }}</dd>

                                            <dt class="col-sm-4">Mata Pelajaran</dt>
                                            <dd class="col-sm-8">: {{ $item->subjects->subject }}</dd>

                                            <dt class="col-sm-4">Kelas</dt>
                                            <dd class="col-sm-8">: {{ $item->kelas->class }} / Semester {{ $item->kelas->semester }}</dd>

                                            <dt class="col-sm-4">Tanggal Ujian</dt>
                                            <dd class="col-sm-8">: {{ $item->date}}</dd>

                                            <dt class="col-sm-4">Waktu Ujian</dt>
                                            <dd class="col-sm-8">: {{ $item->time}}</dd>

                                            <dt class="col-sm-4">Jumlah Soal</dt>
                                            <dd class="col-sm-8">: {{ $item->qnaExams->count() }}</dd>

                                            <dt class="col-sm-4">Token</dt>
                                            <dd class="col-sm-8"><input type="text" name="token" id="token" class="form-control"></dd>
                                        </dl>
                                        <div class="d-grid mt-3 d-md-flex justify-content-md-end">
                                            <button class="btn btn-success" type="submit">Mulai</button>
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
                        @php
                            $foundActiveExams = true;
                        @endphp
                    @endif
                @endforeach

                @if (!$foundActiveExams)
                    <div class="col-lg-12">
                        <div class="alert alert-info" role="alert">
                            Tidak ada ujian yang aktif saat ini.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
