<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Admin QR Code') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-4 sm:p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin QR Code for {{ $user->name }}</h3>
                    <div class="flex justify-center items-center overflow-auto">
                        <div class="max-w-full">
                            {!! $qrCode !!}
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        This QR code contains your admin information and can be scanned by super admin for verification purposes.
                    </p>

                    <div class="mt-4 sm:mt-6 space-y-3 sm:space-y-4">
                        <a href="{{ route('admin.qr.download') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            Download QR Code
                        </a>

                        <div class="mt-3 sm:mt-4">
                            <a href="{{ route('admin.qr.show') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                Refresh QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>