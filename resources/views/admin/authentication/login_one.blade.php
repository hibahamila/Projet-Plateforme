@extends('admin.authentication.master')

@section('title')login
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
    <section>
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-xl-7"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/2.jpg') }}" alt="looginpage" /></div>
	            <div class="col-xl-5 p-0">
	                <div class="login-card">
	                    <form class="theme-form login-form" method="POST" action="{{ route('login') }}">
							@csrf
	                        <h4>Login</h4>
	                        <h6>Welcome back! Log in to your account.</h6>
	                        <div class="form-group">
	                            <label>Email Address</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="icon-email"></i></span>
	                                <input class="form-control @error('email') is-invalid @enderror" type="email" required="" placeholder="Test@gmail.com" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />

									@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label>Password</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="icon-lock"></i></span>
	                                <input  type="password" name="password" required="" placeholder="*********"  class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"/>
	                                <div class="show-hide"><span class="show"> </span></div>
									@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="checkbox">
	                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
	                                <label class="text-muted" for="checkbox1">Remember password</label>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <button class="btn btn-primary btn-block" type="submit">Sign in</button>
	                        </div>


	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>


    @push('scripts')
    @endpush

@endsection
