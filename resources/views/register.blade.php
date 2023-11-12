@extends('layout.layout-common')

@section('space-work')
    <h1>Register</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
    @endif
    <form action={{ route('studentRegister') }} method="post">
        @csrf
        <input type="text" name="name" placeholder="Enter name">
        <input type="email" name="email" placeholder="Enter email">
        <input type="password" name="password" placeholder="Enter password">
        <input type="password" name="password_confirmation" placeholder="Enter password confirmation">
        <input type="submit" value="Register">
    </form>

    @if (Session::has('success'))
        <p style="color:green;">{{ Session::get('success') }}</p>
    @endif

@endsection


