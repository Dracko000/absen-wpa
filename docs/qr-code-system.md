# QR Code System Documentation

This document explains the QR code system in the attendance application, which is built using the SimpleSoftwareIO Simple-QRCode package.

## Overview

The QR code system enables secure attendance tracking through unique QR codes generated for users and attendance sessions. The system uses the `simplesoftwareio/simple-qrcode` package for generating QR codes.

## Features

### Enhanced QR Code Generation

- **Multiple Formats**: Support for SVG, PNG, and EPS formats
- **Customizable Size**: QR codes can be generated in different sizes
- **Styling Options**: Enhanced visual appearance with proper margins and encoding
- **Performance Optimization**: Caching mechanism to avoid unnecessary regeneration

### Data Structure

#### User QR Code
```json
{
  "type": "user_type (e.g. student, admin, superadmin)",
  "id": "user_id",
  "name": "user_name",
  "email": "user_email",
  "class_id": "user's_class_id",
  "class_name": "user's_class_name",
  "timestamp": "generation_timestamp",
  "system": "attendance_system",
  "version": "1.0"
}
```

#### Attendance QR Code
```json
{
  "type": "attendance",
  "schedule_id": "schedule_id",
  "date": "schedule_date",
  "class_id": "class_id",
  "created_at": "generation_timestamp",
  "version": "1.0"
}
```

## Service Class: `App\Services\QRCodeService`

### Methods

#### `generateUserQR($user, $format = 'svg', $size = 300)`
Generates a QR code for a user with the specified format and size.
- **Parameters**: 
  - `$user`: User object
  - `$format`: Output format ('svg', 'png', 'eps')
  - `$size`: QR code size in pixels
- **Returns**: Generated QR code image

#### `generateAttendanceQR($scheduleId, $date, $classId, $format = 'svg', $size = 300)`
Generates a QR code for an attendance session.
- **Parameters**:
  - `$scheduleId`: ID of the schedule
  - `$date`: Date of the schedule
  - `$classId`: ID of the class
  - `$format`: Output format ('svg', 'png', 'eps')
  - `$size`: QR code size in pixels
- **Returns**: Generated QR code image

#### `decodeQRData($qrData)`
Decodes QR code data and validates its structure.
- **Parameters**: `$qrData` - QR code data string
- **Returns**: Decoded data array

### Caching

The system implements caching to improve performance:
- User QR codes are cached for 5 minutes with a unique key based on user ID and last update time
- Attendance QR codes are cached for 5 minutes with a unique key based on schedule ID and parameters
- Cache automatically invalidates when user data changes

## Controllers

### `App\Http\Controllers\QRCodeController`

Handles QR code generation and display requests:

- `generateUserQR()` - Generates QR code image for authenticated user
- `showUserQR()` - Shows user QR code in view
- `generateAttendanceQR()` - Generates attendance session QR code
- `showFullQR()` - Shows full-screen QR code view
- `downloadUserQR()` - Allows downloading user QR code
- `showAdminQR()` - Shows admin QR code
- `downloadAdminQR()` - Allows downloading admin QR code
- `decodeQR()` - Decodes QR data for scanning

### Request Parameters

All QR code generation endpoints accept optional parameters:
- `format` - Output format ('svg', 'png', 'eps'; default: 'svg')
- `size` - QR code size in pixels (default: 300)

## Usage Examples

### In Views
```php
// Generate user QR code in SVG format with size 250
$qrCode = (new QRCodeService())->generateUserQR($user, 'svg', 250);
```

### In Controllers
```php
// Generate attendance QR code in PNG format
$qrCode = $this->qrCodeService->generateAttendanceQR($scheduleId, $date, $classId, 'png', 400);
```

### In Livewire Components
```php
// Generate QR code for display
$qrCode = $this->qrCodeService->generateUserQR($user, 'svg', 250);
```

## Endpoints

- `GET /qr-code` - Show user QR code
- `GET /qr-code/generate` - Generate QR code image
- `GET /qr-code/full` - Show full-screen QR code
- `GET /qr-code/download` - Download user QR code
- `GET /admin-qr` - Show admin QR code
- `GET /admin-qr/download` - Download admin QR code
- `POST /qr-code/debug` - Debug QR code data

## Security Considerations

1. QR codes contain only necessary user information (ID, role, name, email, timestamp, system identifier, version)
2. Only authenticated users can access their own QR codes
3. Only admins/superadmins can generate attendance QR codes
4. QR code data is JSON-encoded with validation
5. System includes version information for future compatibility
6. Scanning process validates QR code data integrity and user ID matching
7. Multiple role type values are accepted during scanning ('user', 'student', 'User', 'Student')

## Scanning Process Compatibility

The scanning system is designed to work with the enhanced QR code data structure:

- Accepts multiple user type values: 'user', 'student', 'User', 'Student'
- Validates that the QR code ID matches the system user
- Returns detailed student information after successful scan
- Provides clear error messages for invalid QR codes

## Performance Notes

- QR codes are cached for 5 minutes after generation
- SVG format is recommended for web display due to smaller file size
- PNG format is recommended for downloads when pixel-perfect rendering is required
- Use appropriate size values (200-500px) for optimal scanning

## Static QR Code Behavior

- QR codes are static per user and contain the user's assigned class information
- If a student changes classes, their QR code will be automatically regenerated to reflect the new class
- QR codes remain consistent for a period but update when critical user information changes (like class assignment)

## Scanning Optimization

- QR codes now include increased margins (2px) for better scannability
- PNG QR codes use highest error correction level (H) for resilience
- PNG QR codes have enhanced color contrast (black on white) for better recognition
- Added troubleshooting tips in the scanning interface