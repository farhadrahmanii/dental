# Dental Practice PWA - Offline-First Laravel Application

A comprehensive **Laravel 11 + PWA** application that works completely offline for up to **one month** and automatically syncs all stored offline data when the device reconnects to the internet.

## 🚀 Features

### ✅ Offline-First Architecture
- **Complete offline functionality** for up to 1 month
- **Automatic background sync** when connection is restored
- **IndexedDB storage** using Dexie.js for local data persistence
- **Service Worker** with Workbox for caching and background sync

### ✅ PWA Capabilities
- **Installable** on desktop and mobile devices
- **App-like experience** with standalone display mode
- **Offline-first** with intelligent caching strategies
- **Push notifications** support
- **Background sync** for seamless data synchronization

### ✅ Modern Tech Stack
- **Laravel 11** backend with REST API
- **Vue 3** frontend with Composition API
- **Pinia** for state management
- **Dexie.js** for IndexedDB operations
- **Workbox** for service worker management
- **Laravel Sanctum** for API authentication
- **Tailwind CSS** for modern UI

### ✅ Task Management System
- **CRUD operations** for tasks (Create, Read, Update, Delete)
- **Priority levels** (Low, Medium, High)
- **Due dates** and completion tracking
- **Real-time sync status** indicators
- **Offline queue** management

## 📋 Requirements

- PHP 8.2 or higher
- Node.js 18 or higher
- npm or yarn
- SQLite (default) or MySQL/PostgreSQL
- Modern web browser with PWA support

## 🛠️ Installation

### 1. Clone and Setup

```bash
# Clone the repository
git clone <repository-url>
cd dentist

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
touch database/database.sqlite
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 4. Build Assets

```bash
# Build for development
npm run dev

# Build for production
npm run build
```

### 5. Start Development Server

```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite dev server
npm run dev
```

Visit `http://localhost:8000/pwa` to access the PWA application.

## 🔧 Configuration

### Environment Variables

Add these to your `.env` file:

```env
# PWA Configuration
PWA_ENABLED=true
PWA_CACHE_STRATEGY=networkFirst
PWA_CACHE_DURATION=86400
PWA_OFFLINE_REDIRECT=/pwa

# Sync Configuration
SYNC_BATCH_SIZE=50
SYNC_RETRY_ATTEMPTS=3
SYNC_RETRY_DELAY=5000
SYNC_CLEANUP_DAYS=30

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,::1
SANCTUM_TOKEN_EXPIRATION=1440
```

### Database Configuration

The application uses SQLite by default. To use MySQL or PostgreSQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dental_pwa
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 📱 PWA Features

### Installation
- **Desktop**: Click the install button in the address bar
- **Mobile**: Use "Add to Home Screen" option
- **Automatic**: App prompts users to install when appropriate

### Offline Functionality
- **Complete offline operation** for up to 1 month
- **Local data storage** using IndexedDB
- **Intelligent caching** of static assets
- **Background sync** when connection is restored

### Sync Process
1. **Offline actions** are queued locally
2. **Connection detection** triggers automatic sync
3. **Batch processing** of queued operations
4. **Conflict resolution** and error handling
5. **Status indicators** show sync progress

## 🔄 API Endpoints

### Tasks API
```
GET    /api/v1/tasks          # List all tasks
POST   /api/v1/tasks          # Create new task
GET    /api/v1/tasks/{id}     # Get specific task
PUT    /api/v1/tasks/{id}     # Update task
DELETE /api/v1/tasks/{id}     # Delete task
```

### Sync API
```
POST   /api/v1/sync           # Sync offline data
GET    /api/v1/sync/status    # Get sync status
```

### Example API Usage

```javascript
// Create a task
const response = await fetch('/api/v1/tasks', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    title: 'New Task',
    description: 'Task description',
    priority: 2,
    due_date: '2024-12-31'
  })
});

const task = await response.json();
```

## 🗄️ Database Schema

### Tasks Table
```sql
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    completed BOOLEAN DEFAULT FALSE,
    priority INTEGER DEFAULT 1,
    due_date TIMESTAMP NULL,
    client_id VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Offline Sync Table
```sql
CREATE TABLE offline_syncs (
    id BIGINT PRIMARY KEY,
    model_type VARCHAR(255) NOT NULL,
    model_id VARCHAR(255) NOT NULL,
    action VARCHAR(255) NOT NULL,
    data JSON NOT NULL,
    client_id VARCHAR(255) NOT NULL,
    synced_at TIMESTAMP NULL,
    is_synced BOOLEAN DEFAULT FALSE,
    error_message TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## 🔧 Maintenance Commands

### Clean Old Sync Data
```bash
# Clean synced data older than 30 days (default)
php artisan sync:clean

# Clean synced data older than 7 days
php artisan sync:clean --days=7
```

### Clear Application Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🧪 Testing

### Run Tests
```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage
```

### Manual Testing
1. **Offline Testing**: Disable network connection and test functionality
2. **Sync Testing**: Re-enable connection and verify data sync
3. **PWA Testing**: Install app and test offline capabilities
4. **Performance Testing**: Test with large datasets

## 🚀 Deployment

### Production Build
```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Build assets for production
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Server Requirements
- **PHP 8.2+** with required extensions
- **Web server** (Apache/Nginx)
- **SSL certificate** (required for PWA)
- **Database** (MySQL/PostgreSQL recommended for production)

### Environment Setup
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
# ... other production settings
```

## 🔒 Security

### API Security
- **Laravel Sanctum** for API authentication
- **CSRF protection** for web routes
- **Input validation** on all endpoints
- **Rate limiting** for API requests

### Data Security
- **Encrypted local storage** for sensitive data
- **Secure sync** with server validation
- **HTTPS required** for PWA functionality
- **Content Security Policy** headers

## 📊 Performance

### Optimization Features
- **Lazy loading** of components
- **Efficient caching** strategies
- **Minimal bundle size** with tree shaking
- **Service worker** caching
- **Database indexing** for fast queries

### Monitoring
- **Sync status** tracking
- **Error logging** and reporting
- **Performance metrics** collection
- **Offline usage** analytics

## 🐛 Troubleshooting

### Common Issues

#### Service Worker Not Registering
```bash
# Check browser console for errors
# Verify HTTPS in production
# Clear browser cache and reload
```

#### Sync Not Working
```bash
# Check network connectivity
# Verify API endpoints are accessible
# Check browser console for errors
# Run: php artisan sync:clean
```

#### PWA Not Installable
```bash
# Ensure HTTPS is enabled
# Check manifest.json is accessible
# Verify service worker is registered
# Test on different browsers
```

### Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- **Laravel** for the excellent PHP framework
- **Vue.js** for the reactive frontend framework
- **Dexie.js** for IndexedDB wrapper
- **Workbox** for service worker tools
- **Tailwind CSS** for utility-first CSS

## 📞 Support

For support and questions:
- **Email**: support@dentalpwa.com
- **Documentation**: [Wiki](link-to-wiki)
- **Issues**: [GitHub Issues](link-to-issues)

---

**Built with ❤️ for modern dental practices**