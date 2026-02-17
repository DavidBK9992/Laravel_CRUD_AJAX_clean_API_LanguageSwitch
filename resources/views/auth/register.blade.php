<x-layout>
    <x-header>
        <x-slot name="header">
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Create Account</h2>
            <p class="text-sm text-gray-500">(Insert Name, Email and Password)</p>
        </x-slot>

            @if ($errors->any())
            <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
   <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
            @csrf
         <div>
        <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
        <div class="mt-2">
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded border px-3 py-2">
        </div>
      </div>
      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
        <div class="mt-2">
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded border px-3 py-2">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
        </div>
        <div class="mt-2">
                <input id="password" name="password" type="password" required class="w-full rounded border px-3 py-2">
        </div>
      </div>
      <div>
        <div class="flex items-center justify-between">
          <label for="password-confirmation" class="block text-sm/6 font-medium text-gray-900">Confirm Password</label>
        </div>
        <div class="mt-2">
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded border px-3 py-2">
        </div>
      </div>


      <div class="flex justify-center">
          <x-button type="submit" href="/">Sign up</x-button>      
      </div>
    </form>

    <p class="mt-10 text-center text-sm/6 text-gray-500">
      Already signed up?
      <a href="#" class="font-semibold text-gray-600 hover:text-gray-500">Click here to go to Login</a>
    </p>
  </div>
</div>

    </x-header>
    </x-layout>