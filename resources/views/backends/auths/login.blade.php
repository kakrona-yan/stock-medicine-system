@extends('backends.layouts.auth')
@section('title', 'RRPS-PAAMA | login')
@section('content')
<div class="container">
    <!-- Row -->
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-6 d-none d-lg-block bg-auth-image "></div>
                <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                    <h1 class="h-s-1 text-pink mb-4 font-national-cartoon">RRPS PAAMA</h1>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST" class="user">
                        @csrf
                        @if(Session::has('login'))
                            <div class="alert alert-danger">
                                <span>{{ Session::get('login') }}</span>
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control form-control-user {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ old('email', $request->email) }}" 
                                placeholder="email"/>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control form-control-user {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    placeholder="password"
                                />
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-circle btn-pink btn-cicle btn-block">Login</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
