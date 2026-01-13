# Raj Water - Water Treatment Business Management System

A comprehensive Customer Relationship Management (CRM) and business management system built for water treatment/purification businesses. This Laravel-based application manages the complete sales cycle from inquiry to order booking, including quotation generation, follow-ups, invoicing, and reporting.

## Project Overview

Raj Water is a full-featured business management system designed to streamline operations for water treatment businesses. It handles customer inquiries, generates quotations (with revision support), manages follow-ups, processes orders, creates invoices, and provides comprehensive reporting capabilities. The system includes role-based access control, zone-based employee management, and integrated communication features (Email & SMS).

## Tech Stack

### Backend
- **Framework**: Laravel 5.2
- **PHP Version**: >= 5.5.9
- **Database**: MySQL
- **PDF Generation**: barryvdh/laravel-dompdf (v0.8.1)

### Frontend
- **CSS Framework**: Bootstrap 3.x
- **JavaScript**: jQuery
- **Build Tool**: Gulp with Laravel Elixir
- **UI Components**: Select2, CKEditor, various plugins

### Third-Party Integrations
- **SMS Gateway**: buzz.azmarq.com (HTTP API)
- **Email**: SMTP (Gmail configured)
- **PDF Library**: DomPDF

## Folder Structure

```
raj_water-master/
├── app/
│   ├── Console/          # Artisan commands and scheduled tasks
│   ├── Events/           # Event classes
│   ├── Exceptions/       # Exception handlers
│   ├── Http/
│   │   ├── Controllers/  # 70+ controllers handling business logic
│   │   ├── Middleware/   # Authentication and CSRF middleware
│   │   └── routes.php    # Application routes
│   ├── Libraries/        # Utility classes (encryption, helpers)
│   ├── Models/          # Eloquent models (Data_model)
│   └── User.php         # User model
├── bootstrap/           # Application bootstrap files
├── config/              # Configuration files (database, mail, etc.)
├── database/
│   ├── migrations/      # Database migrations
│   └── seeds/           # Database seeders
├── external/            # Static assets (images, PDFs, catalogs, documents)
├── public/              # Public web root
├── resources/
│   ├── assets/          # Source assets (SCSS)
│   ├── lang/            # Language files
│   └── views/           # Blade templates (187 views)
├── storage/             # Logs, cache, uploaded files
└── tests/               # PHPUnit tests
```

## Feature List

### Core Modules

#### 1. **Authentication & Authorization**
- User login/logout
- Password reset functionality
- Role-based access control (RBAC)
- User rights management
- Employee password management

#### 2. **Master Data Management**
- **Employee Management**: Employee CRUD, role assignment, zone assignment, transfer functionality
- **Role Management**: Define and manage user roles
- **Product Management**: Products, categories, specifications
- **Quotation Products**: Separate product catalog for quotations
- **Geographic Data**: Country, State, City management with zone assignment
- **Client Categories**: Customer classification
- **Source Management**: Lead source tracking
- **Zone Management**: Geographic zone configuration
- **Address Master**: Multiple office/branch addresses
- **Email Templates**: Quotation and follow-up email templates
- **SMS Format**: Customizable SMS message templates
- **Letterhead Management**: Company letterhead configuration
- **Terms & Conditions**: Editable terms and conditions
- **Minimum Days**: Configurable minimum days for various operations
- **Rate Master**: Product pricing by zone
- **Big Zone Amount**: Special pricing for large zones
- **Party Master**: Vendor/supplier management

#### 3. **Inquiry Management**
- Create, edit, view, and delete inquiries
- Auto-generated inquiry numbers (RW/YYYY-YY/INQ_XXX format)
- Customer data validation (mobile, email uniqueness)
- Zone-based rate calculation
- Inquiry status tracking (Active, Pending, Cancel, Delete)
- Source tracking
- Project value management

#### 4. **Quotation Management**
- Generate quotations from inquiries
- **Sample Quotations**: Template quotations for quick generation
- **Quotation Types**: Simple and GST quotations
- **Quotation Revision**: Multiple revision support with history
- **Print Formats**: 
  - Standard quotation print
  - Simple quotation print
  - GST quotation print
  - With/without letterhead options
- **Email Integration**: Send quotations via email
- **SMS Integration**: Send quotation SMS notifications
- **Reminder System**: Automated reminder emails
- **Quotation Status Master**: Custom status management
- **Quotation Transfer**: Transfer quotations between employees
- **Cancel Inquiry**: Cancel inquiries with reason tracking

#### 5. **Follow-up Management**
- Comprehensive follow-up tracking
- **Follow-up Types**: Multiple follow-up methods (call, email, visit, etc.)
- **Document Management**: Upload and track customer documents
- **Visitor Management**: Track site visits with form numbers
- **Status Updates**: 
  - Revise quotation
  - Price issue tracking
  - Hot list management
  - Order booking
  - Regret tracking
- **Follow-up Way Master**: Customize follow-up methods
- **Project Division Master**: Project categorization
- **Raw Water Master**: Water source tracking
- **Site Status Master**: Site condition tracking
- **Planning Stage Master**: Project planning stages
- **Visit Details Master**: Visit type categorization
- **Payment Mode Master**: Payment method tracking
- **Water Report Master**: Water quality report types
- **Power Supply Master**: Power supply status
- **Document Name Master**: Document type management

#### 6. **Order Management**
- **Order Booking**: Convert quotations to orders
- Order view and print
- Order activation/deactivation
- Add orders to follow-up
- Order book reports

#### 7. **Invoice Management**
- **Proforma Invoice**: Generate proforma invoices
- Invoice PDF generation
- Invoice search and filtering
- SMS notifications for invoices

#### 8. **Job Card Management**
- Job card creation and tracking
- Job card PDF generation
- Job card search functionality

#### 9. **Power Calculation**
- Power requirement calculation
- HP-based power calculations
- Power calculation reports
- PDF generation for power calculations

#### 10. **Customer Management**
- Customer profile management
- Customer search and filtering
- Customer reports
- Customer data validation

#### 11. **Reporting System**
- **Inquiry Report**: Filtered inquiry reports
- **Hot List Report**: High-priority inquiry reports
- **Order Book Report**: Order tracking reports
- **Regret Report**: Lost opportunity reports
- **Customer Report**: Customer analysis reports
- **Visiting Report**: Site visit reports
- **Work Report**: Employee work reports
- **Detail Work Report**: Detailed employee activity reports

#### 12. **Special Lists**
- **Hot List**: High-priority inquiries requiring immediate attention
- **Regret List**: Lost opportunities with regret reasons
- **Search**: Global search across all modules

#### 13. **Dashboard**
- Overview of key metrics
- Employee work details
- Sales employee tracking
- Revision notifications
- Quick access to pending tasks

#### 14. **Additional Features**
- **Check Print**: Check printing functionality
- **Catalog Management**: Product catalog PDF management
- **User Rights**: Granular permission management per user/role
- **Employee Transfer**: Transfer employee inquiries
- **Quotation Transfer**: Transfer quotations between employees

## Application Flow

### High-Level Workflow

```
1. Inquiry Creation
   ↓
2. Customer Validation (Mobile/Email check)
   ↓
3. Zone-based Rate Calculation
   ↓
4. Quotation Generation (from inquiry or sample)
   ↓
5. Quotation Email/SMS to Customer
   ↓
6. Follow-up Management
   ├── Document Collection
   ├── Site Visits
   ├── Status Updates (Revise, Price Issue, Hot List)
   └── Order Booking
   ↓
7. Order Confirmation
   ↓
8. Proforma Invoice Generation
   ↓
9. Job Card Creation
   ↓
10. Power Calculation (if required)
```

### Detailed Flow

1. **Inquiry Entry**: Sales team creates inquiry with customer details, product requirements, and project specifications
2. **Rate Calculation**: System calculates pricing based on zone, product, and quantity
3. **Quotation Generation**: Generate quotation from inquiry or use sample quotation template
4. **Communication**: Send quotation via email/SMS to customer
5. **Follow-up Tracking**: Log all customer interactions, document uploads, and site visits
6. **Status Management**: Update inquiry status (Hot List, Price Issue, Order Book, Regret)
7. **Order Processing**: Convert quotation to order upon customer confirmation
8. **Invoice Generation**: Create proforma invoice for orders
9. **Job Card**: Generate job card for order execution
10. **Reporting**: Generate various reports for management analysis

## API & Third-Party Integrations

### SMS Integration
- **Provider**: buzz.azmarq.com
- **Method**: HTTP API
- **Usage**: 
  - Quotation SMS notifications
  - Follow-up SMS reminders
  - Proforma invoice SMS alerts
- **Configuration**: SMS format templates stored in database (`sms_format` table)

### Email Integration
- **Provider**: SMTP (Gmail configured)
- **Configuration**: 
  - Host: smtp.gmail.com
  - Port: 587
  - Encryption: TLS
- **Usage**:
  - Quotation emails
  - Follow-up emails
  - Reminder emails
  - Email templates for quotations and follow-ups

### PDF Generation
- **Library**: DomPDF (barryvdh/laravel-dompdf)
- **Usage**:
  - Quotation PDFs
  - Proforma Invoice PDFs
  - Job Card PDFs
  - Power Calculation PDFs
  - Order Book PDFs
  - Reports

## Setup & Installation

### Prerequisites
- PHP >= 5.5.9
- MySQL 5.6+ or MariaDB
- Composer
- Node.js & NPM (for frontend assets)
- Web server (Apache/Nginx)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd raj_water-master
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   - Copy `.env.example` to `.env` (if exists) or create `.env` file
   - Update database credentials in `config/database.php` or `.env`
   - Update mail configuration in `config/mail.php` or `.env`

5. **Set application key**
   ```bash
   php artisan key:generate
   ```

6. **Create database**
   ```sql
   CREATE DATABASE raj_water;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Import database schema** (if SQL dump available)
   ```bash
   mysql -u root -p raj_water < database_dump.sql
   ```

9. **Set permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

10. **Compile frontend assets**
    ```bash
    npm run dev
    # or for production
    npm run prod
    ```

11. **Configure web server**
    - Point document root to `public/` directory
    - Ensure mod_rewrite is enabled (Apache)

12. **Access the application**
    - Navigate to `http://your-domain/` or `http://localhost/raj_water-master/public/`

## Environment Variables

Create a `.env` file in the root directory with the following variables:

```env
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:your-generated-key
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=raj_water
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=pratikdonga.ap@gmail.com
MAIL_FROM_NAME="Raj Water"

SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Note**: The application currently has hardcoded database credentials in `config/database.php`. It's recommended to move these to `.env` file for better security.

## Running the Project

### Local Development

1. **Start PHP built-in server** (for testing)
   ```bash
   php artisan serve
   ```
   Access at: `http://localhost:8000`

2. **Or use your web server** (Apache/Nginx)
   - Configure virtual host pointing to `public/` directory
   - Access via configured domain

3. **Watch for asset changes** (optional)
   ```bash
   npm run dev
   ```

### Production Deployment

1. **Optimize application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Compile assets for production**
   ```bash
   npm run prod
   ```

3. **Set environment**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

4. **Configure web server**
   - Use production-grade web server (Nginx recommended)
   - Enable HTTPS
   - Configure proper file permissions

## Cron Jobs / Background Tasks

Currently, **no scheduled cron jobs** are configured in `app/Console/Kernel.php`. However, the system supports:

- **Email Reminders**: Manual trigger for quotation and follow-up reminders
- **SMS Notifications**: Manual trigger for SMS alerts

**Recommended Cron Setup** (if needed):
```bash
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

## Common Use Cases

### 1. Creating a New Inquiry
1. Navigate to **Inquiry** → **Add**
2. Fill customer details (name, mobile, email, address)
3. Select product, quantity, and specifications
4. System auto-generates inquiry number
5. Save inquiry

### 2. Generating a Quotation
1. Go to **Quotation** module
2. Select inquiry from list
3. Choose sample quotation (optional)
4. Review and adjust rates
5. Generate quotation
6. Send via email/SMS to customer

### 3. Managing Follow-ups
1. Open **Follow-up** module
2. Select inquiry
3. Add follow-up entry (call, email, visit)
4. Upload documents if required
5. Update status (Hot List, Order Book, etc.)

### 4. Converting to Order
1. From Follow-up or Quotation module
2. Click "Order Book"
3. Confirm order details
4. Order is created and moved to Order Book

### 5. Generating Reports
1. Navigate to desired report module
2. Apply filters (date range, employee, status, etc.)
3. View/export report data

## Known Limitations

Based on code analysis:

1. **PHP Version**: Uses deprecated `mcrypt` functions (PHP 5.5.9+), which are removed in PHP 7.2+. The `Utility` class uses `mcrypt_encrypt`/`mcrypt_decrypt` which need to be migrated to `openssl`.

2. **Security Concerns**:
   - Database credentials hardcoded in `config/database.php`
   - Email credentials hardcoded in `config/mail.php`
   - Password hashing uses MD5 (should use bcrypt/argon2)
   - SQL queries may be vulnerable to injection (raw queries in some places)

3. **Laravel Version**: Uses Laravel 5.2 (released 2015), which is outdated and no longer receives security updates.

4. **Session Management**: Uses file-based sessions; may need Redis/database sessions for scalability.

5. **No API Layer**: All routes are web-based; no REST API for mobile/third-party integration.

6. **Frontend Dependencies**: Uses older versions of Bootstrap and jQuery; may have security vulnerabilities.

7. **Error Handling**: Limited error handling and logging in some controllers.

8. **Code Duplication**: Multiple controllers have similar code patterns that could be refactored.

## Future Improvements

Based on code structure and common best practices:

1. **Security Enhancements**:
   - Migrate from MD5 to bcrypt/argon2 for password hashing
   - Replace mcrypt with OpenSSL encryption
   - Move all credentials to `.env` file
   - Implement proper SQL injection prevention
   - Add CSRF protection verification
   - Implement rate limiting

2. **Laravel Upgrade**:
   - Upgrade to Laravel 8.x or 9.x for security and performance
   - Migrate to modern PHP (7.4+ or 8.x)

3. **API Development**:
   - Create RESTful API for mobile app integration
   - Implement API authentication (JWT/OAuth)

4. **Code Quality**:
   - Refactor duplicate code into services/repositories
   - Implement proper validation using Form Requests
   - Add comprehensive error handling
   - Implement logging (Monolog)

5. **Database**:
   - Add database migrations for all tables
   - Implement proper foreign key constraints
   - Add database indexes for performance

6. **Testing**:
   - Add unit tests for critical business logic
   - Add integration tests for workflows
   - Implement CI/CD pipeline

7. **Performance**:
   - Implement caching (Redis/Memcached)
   - Optimize database queries
   - Add pagination for large datasets
   - Implement lazy loading for relationships

8. **Features**:
   - Real-time notifications (WebSockets)
   - Advanced reporting with charts/graphs
   - Export functionality (Excel, CSV)
   - Multi-language support
   - Audit logging for critical operations

9. **Documentation**:
   - API documentation (if API is added)
   - User manual
   - Developer documentation

## Contribution Guidelines

1. **Fork the repository** and create a feature branch
2. **Follow coding standards**:
   - Use PSR-2 coding style
   - Write meaningful commit messages
   - Add comments for complex logic
3. **Test your changes** before submitting
4. **Update documentation** if adding new features
5. **Submit a pull request** with clear description of changes

**Note**: Before contributing, ensure you understand the business logic and test thoroughly in a development environment.

## License

**Not specified** - Please check with the project owner for licensing information.

---

## Support & Contact

For issues, questions, or feature requests, please contact the development team or create an issue in the repository.

**Last Updated**: Based on codebase analysis as of current date.
