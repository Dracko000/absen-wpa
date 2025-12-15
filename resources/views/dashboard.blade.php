<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ now()->format('l, d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isSuperAdmin())
                @livewire('super-admin-dashboard')
            @elseif(auth()->user()->isAdmin())
                @livewire('admin-dashboard')
            @else
                @livewire('student-dashboard')
            @endif
        </div>
    </div>
</x-app-layout>
