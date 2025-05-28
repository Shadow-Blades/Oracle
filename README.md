# RDC Weighbridge Management System

A comprehensive solution for managing weighbridge operations and raw material delivery tracking at distribution centers.

## Features

- Multi-user role-based system (Admin, Collector, Driver, Vendor, Material Officer)
- Vehicle tracking with RFID integration
- Weighbridge operations management
- Transaction processing and logging
- Vendor relationship management
- Real-time weight capture
- Invoice generation
- Reporting and analytics

## Technology Stack

- PHP
- Microsoft SQL Server
- RESTful API endpoints
- PHPMailer for notifications

## Installation

1. Clone the repository
   ```
   git clone https://github.com/Shadow-Blades/Oracle.git
   ```

2. Create configuration file
   ```
   cp config.template.php config.php
   ```

3. Edit config.php with your database credentials

4. Set proper permissions
   ```
   chmod 755 -R ./
   chmod 777 -R ./images/uploads/
   ```

5. Access the application at your configured web server

## Directory Structure

```
/
├── API/                 # API endpoints for mobile/external access
├── images/              # System images and uploads
├── vendor/              # Third-party libraries
│   ├── phpmailer/       # Email functionality
│   └── composer/        # Dependency management
└── [PHP files]          # Core application files
```

## Security Notice

This repository does not contain sensitive configuration files, logs, or user data. You must configure the system with your own credentials.

## License

Proprietary - All rights reserved 