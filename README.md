# HATS HRMS

A modern, comprehensive Human Resource Management System built with Laravel, designed to streamline employee lifecycle management, from recruitment and onboarding to payroll and performance evaluation.

## 🌟 Key Features

- **Employee Management & Onboarding**: Multi-step onboarding wizard with auto-generated credentials, centralized 201 file management, and department/role assignments.
- **Leave & Attendance Tracking**: Automated leave policy assignment, prorated entitlement generation, leave ledger management, and attendance corrections.
- **Payroll System**: Integrated compensation tracking and automated payroll draft generation.
- **Recruitment & Applicant Tracking**: Manage job postings, track applicants, evaluate candidates, and convert successful applicants directly into employees.
- **Performance Reviews**: Systematic performance evaluation tracking and administration.
- **Role-Based Access Control (RBAC)**: Fine-grained permissions for HR Admins, Managers, and standard Employees.
- **Audit Logging**: Comprehensive system activity tracking for security and compliance.

## 🛠 Tech Stack

- **Framework**: Laravel 11+
- **Language**: PHP 8.4
- **Frontend**: Blade components, Tailwind CSS v4, Alpine.js
- **Testing**: Pest PHP
- **Development Tools**: Laravel Boost, Laravel Pint

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.3
- Composer
- Node.js & NPM
- SQLite / MySQL / PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/akiyun02/HATS-HRMS.git
   cd HATS-HRMS
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   Copy the example `.env` file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials inside `.env`.*

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Frontend Assets**
   ```bash
   npm run dev
   # or for production: npm run build
   ```

7. **Serve the Application**
   ```bash
   php artisan serve
   ```
   *If using Laravel Herd, the application will be available automatically at `http://hats-hr-portal.test`.*

## 🔒 Security Vulnerabilities

If you discover a security vulnerability within this project, please report it to the repository maintainers rather than using public issue trackers.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
