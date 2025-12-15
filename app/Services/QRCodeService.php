<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Cache;

class QRCodeService
{
    /**
     * Generate QR code for a user
     *
     * @param mixed $user User object with id
     * @param string $format QR code format (svg, png, eps)
     * @param int $size QR code size
     * @return mixed QR code image
     */
    public function generateUserQR($user, $format = 'svg', $size = 300)
    {
        // Create a cache key based on user data, class info and format to ensure QR code updates when class changes
        $cacheKey = "user_qr_{$user->id}_" . md5($format . $size . ($user->updated_at?->timestamp ?? time()) . ($user->class_id ?? 'none'));

        // Check if QR code is already cached
        return Cache::remember($cacheKey, 300, function() use ($user, $format, $size) { // Cache for 5 minutes
            // Ensure we have all required data
            $userData = [
                'type' => $user->role ?? 'user',
                'id' => $user->id,
                'name' => $user->name ?? 'Unknown User',
                'email' => $user->email ?? null,
                'class_id' => $user->class_id, // Include student's class ID
                'class_name' => $user->class?->name, // Include class name for reference
                'timestamp' => now()->toISOString(),
                'system' => 'attendance_system', // Add system identifier
                'version' => '1.0' // Add version for possible future compatibility
            ];

            // Validate that no critical data is missing
            if (empty($userData['id'])) {
                throw new \Exception('User ID is required to generate QR code');
            }

            $data = json_encode($userData);

            if ($data === false) {
                throw new \Exception('Failed to encode QR data as JSON');
            }

            // Create QR code with enhanced styling for better scannability
            $qrCodeGenerator = QrCode::size($size)
                ->margin(2)  // Increased margin for better scannability
                ->encoding('UTF-8');

            // Apply different styling based on format
            if ($format === 'png') {
                $qrCodeGenerator = $qrCodeGenerator
                    ->format('png')
                    ->errorCorrection('H'); // Highest error correction for better scanning
            } elseif ($format === 'eps') {
                $qrCodeGenerator = $qrCodeGenerator->format('eps');
            } else {
                $qrCodeGenerator = $qrCodeGenerator->format('svg');
            }

            // Add colors only for PNG format (SVG handles styling differently)
            if ($format === 'png') {
                $qrCodeGenerator = $qrCodeGenerator
                    ->color(0, 0, 0)          // Black foreground for high contrast
                    ->backgroundColor(255, 255, 255); // White background for high contrast
            }

            return $qrCodeGenerator->generate($data);
        });
    }

    /**
     * Decode QR code data
     *
     * @param string $qrData QR code data string
     * @return array Decoded data
     */
    public function decodeQRData($qrData)
    {
        if (empty($qrData)) {
            throw new \Exception('QR data is empty');
        }

        $decoded = json_decode($qrData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON in QR code data: ' . json_last_error_msg());
        }

        if (!$decoded) {
            throw new \Exception('Invalid QR code data - not a valid JSON object');
        }

        // Check if required fields are present
        $requiredFields = ['type', 'id', 'name', 'timestamp'];
        foreach ($requiredFields as $field) {
            if (!isset($decoded[$field])) {
                throw new \Exception("Missing required field '{$field}' in QR code data");
            }
        }

        return $decoded;
    }

    /**
     * Generate QR code for attendance check-in
     *
     * @param int $scheduleId Schedule ID
     * @param string $date Date of the schedule
     * @param string $classId Class ID
     * @param string $format QR code format (svg, png, eps)
     * @param int $size QR code size
     * @return mixed QR code image
     */
    public function generateAttendanceQR($scheduleId, $date, $classId, $format = 'svg', $size = 300)
    {
        // Create a cache key based on the parameters
        $cacheKey = "attendance_qr_{$scheduleId}_" . md5($date . $classId . $format . $size);

        return Cache::remember($cacheKey, 300, function() use ($scheduleId, $date, $classId, $format, $size) { // Cache for 5 minutes
            $data = json_encode([
                'type' => 'attendance',
                'schedule_id' => $scheduleId,
                'date' => $date,
                'class_id' => $classId,
                'created_at' => now()->toISOString(),
                'version' => '1.0'
            ]);

            // Create QR code with enhanced styling for better scannability
            $qrCodeGenerator = QrCode::size($size)
                ->margin(2)  // Increased margin for better scannability
                ->encoding('UTF-8');

            // Apply different styling based on format
            if ($format === 'png') {
                $qrCodeGenerator = $qrCodeGenerator
                    ->format('png')
                    ->errorCorrection('H'); // Highest error correction for better scanning
            } elseif ($format === 'eps') {
                $qrCodeGenerator = $qrCodeGenerator->format('eps');
            } else {
                $qrCodeGenerator = $qrCodeGenerator->format('svg');
            }

            // Add colors only for PNG format (SVG handles styling differently)
            if ($format === 'png') {
                $qrCodeGenerator = $qrCodeGenerator
                    ->color(0, 0, 0)          // Black foreground for high contrast
                    ->backgroundColor(255, 255, 255); // White background for high contrast
            }

            return $qrCodeGenerator->generate($data);
        });
    }
}