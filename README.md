# Attendance Application

This is a QR-based attendance management system that supports different user roles including students, admins, and superadmins.

## QR Code System

The application uses SimpleSoftwareIO Simple-QRCode package for generating and processing QR codes. Key features include:

- **Multiple Formats**: Support for SVG, PNG, and EPS formats
- **Customizable Size**: QR codes can be generated in different sizes
- **Enhanced Styling**: Proper margins, encoding, and visual appearance
- **Performance Optimized**: Caching mechanism to avoid unnecessary regeneration
- **Secure Data**: QR codes contain only necessary user information (ID, role, name, timestamp)
- **Scanning Compatibility**: Enhanced scanning process with improved validation and error handling

### QR Code Structure
- User QR codes contain: type, id, name, email, timestamp, system identifier, and version
- Attendance QR codes contain: type, schedule_id, date, class_id, created_at, and version

### Scanning Enhancements
- Accepts multiple user type values: 'user', 'student', 'User', 'Student'
- Validates that QR code ID matches system user
- Returns detailed student information after successful scan
- Provides clear error messages for invalid QR codes

## Admin Registration

The application has special registration links for admins and superadmins that are not publicly accessible:

### Admin Registration (Guru)
- **URL**: `/admin/register`
- **Role**: Automatically set to `admin` (displayed as "Guru")
- **Access**: Manual access only (not linked from application)

### Superadmin Registration
- **URL**: `/superadmin/register`
- **Role**: Automatically set to `super_admin`
- **Access**: Manual access only (not linked from application)

## Usage Notes

- These registration links allow direct creation of admin or superadmin accounts
- The links are not discoverable from normal application navigation
- Only users with knowledge of the URLs can access these registration forms
- Standard user registration is handled through the normal registration flow
- QR codes can be accessed/downloaded from user profile pages
- Admins can scan student QR codes for attendance tracking