@extends('layouts.app')

@section('content')
<div class="mx-auto h-full flex justify-center items-center bg-gray-300">
    <div class="w-96 bg-blue-900 rounded-lg shadow-xl p-6">
            @include('partials/websiteflame')

            <h1 class="text-white text-2xl pt-5">Welcome back</h1>
            <h2 class="text-blue-300">Enter your credentials below</h2>

            <form method="POST" action="{{ route('login') }}" class="pt-8">
                @csrf

                <div class="relative"> {{-- Email--}}
                    <label for="email"
                           class="uppercase text-blue-500 text-xs font-bold pl-3 pt-2 absolute">
                        e-mail
                    </label>

                    <input class="pt-8 w-full p-3 rounded bg-blue-800 text-gray-100 outline-none focus:bg-blue-700"
                               name="email"
                               id="email" type="email"
                               value="{{ old('email') }}" autocomplete="email"
                               autofocus placeholder="your@emal.com">

                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                </div> {{-- Email end --}}

                <div class="relative pt-3"> {{-- password --}}
                    <label for="password"
                           class="uppercase text-blue-500 text-xs font-bold pl-3 pt-2 absolute">
                        Password
                    </label>

                        <input class="pt-8 w-full p-3 rounded bg-blue-800 text-gray-100 outline-none focus:bg-blue-700"
                               id="password" type="password"  name="password"  autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                </div> {{-- password end --}}

                <div class="pt-2">
                    <input class="" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="text-white" for="remember">
                           Remember Me
                    </label>
                </div>


                <div class="pt-8">
                    <button type="submit"
                            class="py-2 px-3 bg-gray-400 text-left rounded uppercase text-blue-800 font-bold w-full">
                        login
                    </button>
                </div>

                <div class="flex justify-between pt-8 text-white text-sm font-bold">
                    <a class="" href="{{ route('password.request') }}">
                        Forgot Password ?
                    </a>
                    <a href="{{ route('register') }}">
                        Register
                    </a>
                </div>



            </form>
    </div>
</div>
@endsection
