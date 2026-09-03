# Deployment Guide - Fix Intervention Image Issue

## Problem
The error "Class 'Intervention\Image\Laravel\Facades\Image' not found" occurs on production server but works locally.

## Solution

### 1. Update Composer Dependencies
Run these commands on your production server:

```bash
# Clear composer cache
composer clear-cache

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Or if you need to update specific packages
composer update intervention/image --no-dev --optimize-autoloader
```

### 2. Clear Laravel Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Verify PHP Extensions
Ensure these PHP extensions are installed on your production server:
- `gd` (for image processing)
- `fileinfo` (for file uploads)

Check with:
```bash
php -m | grep -E "(gd|fileinfo)"
```

### 4. Check File Permissions
Ensure proper permissions for storage and cache directories:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 5. Verify Configuration
The application now uses the direct `ImageManager` approach instead of facades, which should work without additional service provider configuration.

### 6. Test Image Upload
After deployment, test image upload functionality to ensure it's working correctly.

## Changes Made

1. **Updated ImageUploadService**: Changed from using `InterventionImage::read()` to `$this->imageManager->read()`
2. **Removed Facade Dependencies**: No longer relies on Intervention Image facades
3. **Cleaned Up Controllers**: Removed unused facade imports from controllers
4. **Direct ImageManager Usage**: All image processing now uses `ImageManager` with `Gd\Driver`

## Files Modified
- `app/Services/ImageUploadService.php`
- `app/Http/Controllers/Admin/SettingController.php`
- `app/Http/Controllers/Admin/SlideController.php`
- `app/Http/Controllers/Admin/UserController.php`

## Notes
- The application now uses Intervention Image v3 with the direct manager approach
- No service provider registration is required
- Works with both GD and Imagick drivers (configured in `config/image.php`) 
