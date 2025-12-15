<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\QRCodeService;

class ShowUserQr extends Component
{
    protected $qrCodeService;

    public function __construct()
    {
        $this->qrCodeService = new QRCodeService();
    }

    public function render()
    {
        $user = Auth::user();
        $qrCode = $this->qrCodeService->generateUserQR($user, 'svg', 250); // SVG for web display

        return view('livewire.show-user-qr', [
            'qrCode' => $qrCode,
            'user' => $user
        ]);
    }
}
