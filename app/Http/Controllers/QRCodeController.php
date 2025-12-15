<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QRCodeService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Schedule;

class QRCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Generate QR code for the authenticated user
     */
    public function generateUserQR(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 300);

        $qrCode = $this->qrCodeService->generateUserQR($user, $format, $size);

        $contentType = match($format) {
            'png' => 'image/png',
            'eps' => 'application/postscript',
            default => 'image/svg+xml'
        };

        return response($qrCode)->header('Content-type', $contentType);
    }

    /**
     * Show QR code for the authenticated user
     */
    public function showUserQR(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('login');
        }

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 300);

        $qrCode = $this->qrCodeService->generateUserQR($user, $format, $size);

        return view('qrcodes.show', compact('qrCode', 'user'));
    }

    /**
     * Generate QR code for attendance session
     */
    public function generateAttendanceQR(Request $request)
    {
        $user = Auth::user();

        // Only allow admins/superadmins to generate attendance QR codes
        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $schedule = Schedule::findOrFail($request->schedule_id);

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 300);

        $qrCode = $this->qrCodeService->generateAttendanceQR(
            $schedule->id,
            $schedule->date,
            $schedule->class_model_id,
            $format,
            $size
        );

        $contentType = match($format) {
            'png' => 'image/png',
            'eps' => 'application/postscript',
            default => 'image/svg+xml'
        };

        return response($qrCode)->header('Content-type', $contentType);
    }

    /**
     * Decode QR code data (for scanning)
     */
    public function decodeQR(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        try {
            $decodedData = $this->qrCodeService->decodeQRData($request->qr_data);
            return response()->json(['data' => $decodedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Show full screen QR code
     */
    public function showFullQR(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('login');
        }

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 400); // Larger size for full view

        $qrCode = $this->qrCodeService->generateUserQR($user, $format, $size);

        return view('qrcodes.full', compact('qrCode', 'user'));
    }

    /**
     * Download user's QR code
     */
    public function downloadUserQR(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('login');
        }

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 300);

        $qrCode = $this->qrCodeService->generateUserQR($user, $format, $size);

        $contentType = match($format) {
            'png' => 'image/png',
            'eps' => 'application/postscript',
            default => 'image/svg+xml'
        };

        $extension = match($format) {
            'png' => 'png',
            'eps' => 'eps',
            default => 'svg'
        };

        $fileName = $user->name . '_QR_Code.' . $extension;

        return response($qrCode)
            ->header('Content-type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Generate QR code for admin (for superadmin to scan)
     */
    public function showAdminQR(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            return redirect('login');
        }

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 300);

        $qrCode = $this->qrCodeService->generateUserQR($user, $format, $size); // Use same format as user QR

        return view('qrcodes.admin-show', compact('qrCode', 'user'));
    }

    /**
     * Download admin's QR code
     */
    public function downloadAdminQR(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            return redirect('login');
        }

        $format = $request->input('format', 'svg');
        $size = $request->input('size', 300);

        $qrCode = $this->qrCodeService->generateUserQR($user, $format, $size);

        $contentType = match($format) {
            'png' => 'image/png',
            'eps' => 'application/postscript',
            default => 'image/svg+xml'
        };

        $extension = match($format) {
            'png' => 'png',
            'eps' => 'eps',
            default => 'svg'
        };

        $fileName = $user->name . '_Admin_QR_Code.' . $extension;

        return response($qrCode)
            ->header('Content-type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Debug QR code data - decode provided QR data and return as JSON
     */
    public function debugQRData(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $qrData = $request->input('qr_data');

        if (!$qrData) {
            return response()->json(['error' => 'QR data is required'], 400);
        }

        try {
            $decodedData = $this->qrCodeService->decodeQRData($qrData);
            return response()->json(['decoded_data' => $decodedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to decode QR: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Generate QR code optimized for scanning (PNG format)
     */
    public function generateScannableQR()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Generate QR code in PNG format which is better for scanning
        $qrCode = $this->qrCodeService->generateUserQR($user, 'png', 300);

        return response($qrCode)->header('Content-type', 'image/png');
    }
}
