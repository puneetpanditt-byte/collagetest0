# SDGD College Website

A modern, responsive college management system with admin panel for SDGD College, Nauhatta.

## Features

### Frontend
- **Responsive Design**: Mobile-friendly layout
- **Modern UI**: Clean, professional interface
- **Navigation**: Multi-level dropdown menus
- **Dynamic Content**: Notices, tenders, announcements
- **Photo/Video Gallery**: Media management
- **Contact Forms**: User interaction
- **Important Links**: Quick access to resources

### Admin Panel
- **Dashboard**: Statistics and overview
- **Content Management**: Add/edit notices, tenders
- **User Management**: Students and teachers
- **Gallery Management**: Photos and videos
- **Message System**: Contact form responses
- **Activity Logs**: Track all admin activities
- **Settings**: Site configuration

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP (for local development)

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   Copy the project files to your web server directory (htdocs)
   ```

2. **Database Setup**
   - Create a new database named `sdgd_college`
   - Import the `database.sql` file into your database
   ```sql
   CREATE DATABASE sdgd_college;
   USE sdgd_college;
   -- Import database.sql
   ```

3. **Configure Database Connection**
   - Open `config.php`
   - Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'sdgd_college');
   ```

4. **Set File Permissions**
   - Ensure the `uploads/` directory is writable
   ```bash
   chmod 755 uploads/
   ```

5. **Access the Website**
   - Frontend: `http://localhost/web%20collage/`
   - Admin Panel: `http://localhost/web%20collage/admin/`

## Default Login Credentials

### Admin Login
- **Username**: `admin`
- **Password**: `password`
- **URL**: `http://localhost/web%20collage/admin/login.php`

> **Important**: Change the default password after first login for security.

## Directory Structure

```
web collage/
├── admin/                  # Admin panel files
│   ├── login.php          # Admin login page
│   ├── dashboard.php      # Admin dashboard
│   ├── logout.php         # Logout handler
│   ├── notices.php        # Notice management
│   ├── tenders.php        # Tender management
│   ├── students.php       # Student management
│   ├── teachers.php       # Teacher management
│   ├── courses.php        # Course management
│   ├── gallery.php        # Gallery management
│   ├── messages.php       # Contact messages
│   ├── settings.php       # Site settings
│   └── admin-styles.css   # Admin panel styles
├── assets/                # Static assets
│   ├── images/           # Image files
│   ├── documents/        # PDF files
│   └── uploads/          # User uploaded files
├── config.php             # Configuration file
├── database.sql           # Database schema
├── index.php              # Homepage
├── styles.css             # Main stylesheet
├── script.js              # JavaScript functions
└── README.md              # This file
```

## Key Features Explained

### 1. Notice Management
- Add/edit/delete notices
- Categorize notices (general, examination, admission, etc.)
- Set priority levels
- File attachment support
- Auto-expiry functionality

### 2. Tender Management
- Publish tender notices
- Set last dates
- Document attachments
- Status management (active, closed, cancelled)

### 3. User Management
- **Students**: Complete student records
- **Teachers**: Staff management with profiles
- **Admins**: Multi-level admin access

### 4. Gallery System
- Photo gallery with categories
- Video gallery with YouTube integration
- Album organization

### 5. Contact System
- Contact form with email notifications
- Message management in admin panel
- Response tracking

## Security Features

- **CSRF Protection**: Prevents cross-site request forgery
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Input sanitization
- **Session Management**: Secure session handling
- **File Upload Security**: File type and size validation
- **Activity Logging**: Complete audit trail

## Customization

### Adding New Pages
1. Create PHP file in root directory
2. Include `config.php` for database access
3. Use the existing CSS classes for styling
4. Add navigation link to `index.php`

### Modifying Styles
- Main styles: `styles.css`
- Admin styles: `admin/admin-styles.css`
- Responsive design included

### Database Modifications
- Update `database.sql` for schema changes
- Use migration scripts for production

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Performance Optimization

- Optimized images
- Minified CSS/JS
- Database indexing
- Caching headers
- Lazy loading for images

## Support

For issues and support:
1. Check the error logs
2. Verify database connection
3. Check file permissions
4. Ensure PHP requirements are met

## License

This project is for educational purposes. Feel free to modify and use according to your needs.

---

**Developed by**: Web Team  
**Version**: 1.0  
**Last Updated**: December 2025
