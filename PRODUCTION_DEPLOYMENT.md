# Production Deployment Guide

## Vite Asset Building

This application uses Vite for asset compilation. In production, you need to build the assets locally and deploy the built files to the server.

### Building Assets for Production

1. **Build the assets locally:**
   ```bash
   npm install
   npm run build
   ```

2. **Deploy the built files:**
   - Upload the `public/build/` directory to your production server
   - Ensure the directory structure is: `public/build/assets/` with all CSS and JS files

### How It Works

The Blade templates automatically detect the environment:

- **Development** (when `public/hot` file exists): Uses `@vite()` directive to connect to Vite dev server
- **Production** (when `public/build/manifest.json` exists but no `hot` file): Uses built assets from the manifest

### Files Modified

- `resources/views/layouts/pwa.blade.php` - Updated to use built assets in production
- `resources/views/welcome.blade.php` - Updated to use built assets in production

### Troubleshooting

If you see errors about missing assets:

1. Make sure `public/build/manifest.json` exists
2. Verify all files in `public/build/assets/` are present
3. Clear Laravel cache: `php artisan cache:clear` and `php artisan view:clear`
4. Check file permissions on the `public/build` directory

### Notes

- The `public/hot` file is automatically created when running `npm run dev`
- Do NOT commit the `public/hot` file to production
- Always build assets before deploying to production
- The manifest.json file maps source files to built files with hashed names for cache busting

