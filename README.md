# Restaurant POS & Management System

A robust, feature-rich Restaurant Point of Sale (POS) and Management System built with Laravel 12. This system provides comprehensive tools for managing orders, inventory, staff, and reporting with a modern, responsive user interface.

## 🚀 Features

- **Advanced Role & Permission Management**: Powered by Spatie Permissions, allowing granular access control for different staff members (Admin, Manager, Cashier, Kitchen, etc.).
- **Media & File Management**: Integrated with Spatie Media Library for efficient handling of product images, receipts, and other documents.
- **Activity Logging**: Comprehensive audit trails using Spatie Activitylog to track system changes and user actions.
- **Reporting & Exports**: Generate detailed PDF and Excel reports using `barryvdh/laravel-dompdf` and `maatwebsite/excel`.
- **Modern Dashboard**: A sleek, responsive dashboard built with a combination of Tailwind CSS, Bootstrap 5, and custom styling.
- **Interactive UI Components**:
  - **FilePond** for drag-and-drop file uploads
  - **Select2** for advanced, searchable dropdowns
  - **SweetAlert2** for beautiful notifications and alerts
  - **Chart.js** for interactive data visualization
  - **Flatpickr** for elegant date selection
- **RESTful API Support**: Ready for mobile app or external system integration.

## 🛠️ Technology Stack

### Backend
- Laravel 12.x
- PHP 8.2+
- MySQL / PostgreSQL
- Spatie (Permissions, Media Library, Activitylog)
- Laravel Excel & DOMPDF

### Frontend
- Vite (Asset Compilation)
- Tailwind CSS & Bootstrap 5
- Alpine.js & jQuery
- Chart.js, FilePond, Select2, SweetAlert2
- Custom Typography (Inter, Outfit, Instrument Sans)

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or compatible database

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd restaurant-laravel-ai
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   Copy the example env file and set your database credentials:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   # or for development: npm run dev
   ```

7. **Link Storage**
   ```bash
   php artisan storage:link
   ```

8. **Start the Application**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

## 📸 Screenshots

*(Add screenshots of the dashboard, POS screen, and reporting modules here to showcase the UI)*

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the issues page if you want to contribute.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
