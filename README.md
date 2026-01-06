<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12.X"></a>
<a href="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php"><img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2+"></a>
<a href="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql"><img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql" alt="MYSQL"></a>
</p>

# Event Management System - Backend API

A sophisticated Event Management System built with Laravel, featuring a robust RESTful API with comprehensive authentication, authorization, and event management capabilities.

## 🚀 Key Features

### 🔐 **Authentication & Security**
- **Token-based Authentication** using Laravel Sanctum
- **Role-based Authorization** implemented via Gates & Policies
- **API Rate Limiting** for protected endpoints
- **Secure password hashing** with bcrypt

### 📅 **Event Management**
- **CRUD Operations** for events (Create, Read, Update, Delete)
- **Advanced Query Building** with custom trait for dynamic relation loading
- **Data Sorting & Validation** with Laravel's validation system
- **Pagination** for optimized data retrieval
- **Complex Relationship Management** between events, users, and attendees

### 👥 **User & Attendance System**
- **User Registration & Authentication**
- **Attendance Tracking** with many-to-many relationships
- **Custom API Resources** for structured JSON responses
- **Factory & Seeder Implementation** for testing data

### ⏰ **Automated Notifications**
- **Custom Artisan Command** for event reminders
- **Task Scheduling** (daily execution)
- **Email Notifications** to attendees 24 hours before events
- **Queue System** for asynchronous email processing
- **Database Notifications** with Laravel's notification system

## 🏗️ **Technical Architecture**

### **API Design**
- RESTful API endpoints with proper HTTP status codes
- API Resource Controllers for clean separation of concerns
- JSON:API compatible responses
- Request validation with custom rules

### **Database Design**
```php
// Key Relationships
User → hasMany → Event
Event → belongsTo → User
Event → belongsToMany → User (as attendees)
```

### **Advanced Laravel Features Implemented**
1. **Traits** - Custom trait for dynamic relation loading in queries
2. **Policies & Gates** - Comprehensive authorization layer
3. **Service Layer** - Business logic separation
4. **Repository Pattern** - Data access abstraction
5. **Observers** - Model event handling
6. **Events & Listeners** - Decoupled application logic

## 📁 **Project Structure**
```
app/
├── Console/
│   └── Commands/
│       └── SendEventReminders.php
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── EventController.php
│   │   └── AttendeeController.php
│   ├── Resources/
│   │   └── EventResource.php
│   ├── Requests/
│   │   └── StoreEventRequest.php
│   └── Traits/
│       └── LoadRelations.php
├── Models/
│   ├── User.php
│   ├── Event.php
│   └── Attendee.php
├── Policies/
│   └── EventPolicy.php
├── Notifications/
│   └── EventReminderNotification.php
└── Jobs/
    └── SendReminderEmails.php
```

## 🛠️ **Installation & Setup**

### **Prerequisites**
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Laravel 10.x

### **Installation Steps**
```bash
# 1. Clone the repository
git clone https://github.com/yourusername/event-management-system.git
cd event-management-system

# 2. Install dependencies
composer install

# 3. Configure environment
cp .env.example .env
# Update database credentials in .env

# 4. Generate application key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Seed the database
php artisan db:seed

# 7. Start the development server
php artisan serve

# 8. Run the queue worker (in separate terminal)
php artisan queue:work
```

## 📚 **API Documentation**

### **Authentication Endpoints**
```
POST   /api/register     - Register new user
POST   /api/login        - Login user
POST   /api/logout       - Logout user
GET    /api/user         - Get authenticated user
```

### **Event Endpoints**
```
GET    /api/events       - List all events (with pagination)
POST   /api/events       - Create new event (authenticated)
GET    /api/events/{id}  - Get specific event
PUT    /api/events/{id}  - Update event (authorization required)
DELETE /api/events/{id}  - Delete event (authorization required)
```

### **Attendance Endpoints**
```
POST   /api/events/{id}/attend   - Attend an event
DELETE /api/events/{id}/attend   - Cancel attendance
GET    /api/users/{id}/events    - Get user's events
```

## 🔧 **Advanced Features in Detail**

### **1. Dynamic Relation Loading Trait**
```php
// Usage in controllers
$events = Event::withRelations(['creator', 'attendees'])->paginate(10);

// Custom trait implementation
trait LoadRelations
{
    public function scopeWithRelations($query, array $relations)
    {
        return $query->with(array_intersect($relations, $this->loadableRelations));
    }
}
```

### **2. Custom Artisan Command**
```bash
# Manually send reminders
php artisan events:send-reminders

# Scheduled to run daily at 9 AM
# Kernel.php
$schedule->command('events:send-reminders')->dailyAt('09:00');
```

### **3. Queue Implementation**
```php
// Job for sending reminder emails
class SendReminderEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Async email processing
        Notification::send($users, new EventReminderNotification($event));
    }
}
```

### **4. Rate Limiting**
```php
// In route service provider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

## 🧪 **Testing & Quality Assurance**
- **PHPUnit** for unit and feature tests
- **Database Factories** for test data generation
- **Postman Collection** for API testing
- **Validation Rules** for input sanitization
- **Error Handling** with custom exceptions

## 📊 **Performance Optimizations**
- **Eager Loading** with custom trait
- **Database Indexing** on frequently queried columns
- **Queue System** for heavy operations
- **API Caching** strategy
- **Pagination** for large datasets

## 🔒 **Security Features**
- **CSRF Protection** for web routes
- **XSS Prevention** with blade templating
- **SQL Injection Protection** via Eloquent ORM
- **Input Validation** on all endpoints
- **Secure Headers** middleware

## 🚦 **Development Workflow**
1. **Version Control** with Git
2. **Feature Branch Strategy**
3. **Commit Message Convention** following best practices
4. **Code Review** process
5. **Continuous Integration** ready

## 🌟 **What Makes This Project Stand Out**

### **For Your CV - Highlight These:**
✅ **Full-stack Laravel Expertise** - Demonstrated mastery of Laravel's ecosystem  
✅ **API Design Skills** - RESTful principles with proper status codes and error handling  
✅ **Security Consciousness** - Implemented multiple layers of security  
✅ **System Architecture** - Clean separation of concerns and design patterns  
✅ **Real-world Features** - Email notifications, queues, scheduling  
✅ **Code Quality** - Follows Laravel and PHP best practices  
✅ **Problem Solving** - Custom solutions like the LoadRelations trait  

### **Technical Competencies Demonstrated:**
- **Backend Development**: PHP, Laravel, MySQL
- **API Development**: RESTful APIs, JSON responses, Postman testing
- **Authentication**: Laravel Sanctum, token-based auth
- **Authorization**: Gates, Policies, role-based access
- **Database**: Eloquent ORM, migrations, relationships
- **Queue System**: Redis/database queues, job processing
- **Task Scheduling**: Laravel Scheduler, cron jobs
- **Notifications**: Email, database notifications
- **Testing**: PHPUnit, factory data generation

## 📈 **Future Enhancements**
- [ ] WebSocket integration for real-time updates
- [ ] React/Vue.js frontend application
- [ ] Mobile app with React Native
- [ ] Advanced analytics dashboard
- [ ] Payment integration for paid events
- [ ] Social media sharing features
- [ ] Calendar synchronization (Google Calendar, Outlook)

## 🤝 **Contributing**
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

*This project demonstrates professional-grade backend development skills suitable for senior Laravel developer positions.*
