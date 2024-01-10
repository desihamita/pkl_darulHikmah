@extends('layouts.main')

@section('title', 'Dashboard')

@section('titleContent', 'Dashboard')

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $totalStudents }}</h3>

              <p>Siswa/i</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="/admin/students" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $totalExams }}</h3>

              <p>Ujian</p>
            </div>
            <div class="icon">
                <i class="nav-icon fas fa-copy"></i>
            </div>
            <a href="/admin/exam" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $totalQna }}</h3>

              <p>Soal</p>
            </div>
            <div class="icon">
                <i class="nav-icon fas fa-edit"></i>
            </div>
            <a href="/admin/qna-ans" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $totalSubject }}</h3>

              <p>Mata Pelajaran</p>
            </div>
            <div class="icon">
                <i class="nav-icon fas fa-book"></i>
            </div>
            <a href="/admin/subject" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    </div>
  </section>

@endsection
