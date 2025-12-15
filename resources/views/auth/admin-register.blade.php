<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-center">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                {{ isset($default_role) ? (($default_role === 'admin' ? 'Guru' : ucfirst(str_replace('_', ' ', $default_role))) . ' Registration') : 'Admin Registration' }}
            </h2>
        </div>

        <x-validation-errors class="mb-4" :errors="$errors" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ isset($default_role) && $default_role === 'super_admin' ? route('superadmin.register.store') : route('admin.register.store') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            @if(!isset($default_role))
            <div class="mt-4">
                <x-label for="role" value="{{ __('Role') }}" />
                <select name="role" id="role" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Guru</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            @else
            <input type="hidden" name="role" value="{{ $default_role }}">
            <div class="mt-4">
                <x-label value="{{ __('Role') }}" />
                <div class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm p-2 bg-gray-100 dark:bg-gray-700">
                    {{ $default_role === 'admin' ? 'Guru' : ucfirst(str_replace('_', ' ', $default_role)) }}
                </div>
            </div>
            @endif


            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>