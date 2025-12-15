<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Full QR Code View') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 flex items-center justify-center">
        <div class="w-full max-w-xs sm:max-w-md px-4 mx-4 bg-white overflow-hidden shadow-xl rounded-lg p-6 sm:p-8 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4 sm:mb-6">Your QR Code</h3>
            <div class="mb-4 sm:mb-6 flex justify-center items-center overflow-auto">
                <div class="max-w-full">
                    {!! $qrCode !!}
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4 sm:mb-6">Show this QR code to your teacher for attendance</p>

            <div class="space-y-3 sm:space-y-4">
                <a href="{{ route('qr.download') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    Download QR Code
                </a>

                <div class="mt-3 sm:mt-4">
                    <a href="{{ route('qr.show') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Back to QR View
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>