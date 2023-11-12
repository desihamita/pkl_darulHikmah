@extends('layout.layout-common')

@section('space-work')
    <h1>Login</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
    @endif

    @if (Session::has('error'))
        <p style="color:red;">{{ Session::get('error') }}</p>
    @endif

    <form action={{ route('userLogin') }} method="post">
        @csrf
        <input type="email" name="email" placeholder="Enter email">
        <input type="password" name="password" placeholder="Enter password">
        <input type="submit" value="Login">
    </form>
@endsection


