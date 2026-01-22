### Laravel Excel Import

This is an educational project designed to demonstrate advanced Excel file processing in Laravel. This system handles complex data imports with a focus on asynchronous processing, detailed error reporting, and flexible data structures.

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Microsoft Excel](https://img.shields.io/badge/Microsoft_Excel-217346?style=for-the-badge&logo=microsoft-excel&logoColor=white)

---
### Install and settings

```
git clone git@github.com:RevenkoVladislav/LaravelExcel.git
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm vite
php artisan serve
php artisan queue:work
```

For testing purposes, you can use the two sample files included in the project: static.xlsx and dynamic.xlsx.

---
### Key features:
- Task-Based Import: Utilizes a task management system to handle imports with Jobs, allowing for background processing.
- Comprehensive Validation: Real-time data validation during the import process.
- Error Reporting: A dedicated error page that specifies exactly which row failed and why, providing clear feedback for data correction.
- Static & Dynamic Mapping:
    - Static Files: Supports fixed-structure Excel files.
    - Dynamic Columns: Capable of processing files with a variable number of columns.
- A data table displaying imported records with a polished UI design, featuring modal windows for detailed payment information.
- Ability to select import types of both static and dynamic column structures.

### Architecture & Patterns:
- Layered Architecture: Strict separation of concerns using the Service Layer pattern to keep controllers lean.
- Design Patterns: Implemented Builder, Mapper, and Factory patterns for flexible object creation and data transformation.
- Data Transfer Objects (DTO): Used to ensure type safety and structured data flow between layers.
- Resolvers: Dedicated resolver classes for sophisticated data transformation logic.

### Backend (Laravel):
- Asynchronous Processing: Heavy import tasks are handled via Laravel Jobs and Queues to ensure a smooth user experience.
- Laravel Excel Integration: Advanced usage of the Laravel Excel package for parsing and validation.
- API Resources: Data is delivered to the frontend through Laravel Resources, ensuring consistent and clean JSON structures.

### Frontend (Vue.js + Inertia)
- Component-Based Approach: A modular UI built with reusable components.
- Modular Views: Dedicated pages for Tasks (monitoring), Imports (uploading), and Projects (data visualization).
- Interactive UI: Modal windows for displaying granular payment details without page reloads.

---
### Technologies used

- laravel 12
- Vue.js
- Inertia
- Laravel Excel
- Mysql
- Vite
- Breeze

---
### Screenshots

<div><p>Import Page:</p>
<img width="1536" height="735" alt="страница импорта" src="https://github.com/user-attachments/assets/e5d9bb8d-d9e0-400d-a571-3a5b4c00f8e3" />
</div>

---

<div><p>File selected:</p>
<img width="1306" height="668" alt="выбрали файл" src="https://github.com/user-attachments/assets/42f3145f-b786-49c3-8842-ffe48303a292" />
</div>

---

<div><p>Upload processing:</p>
<img width="617" height="390" alt="процесс загрузки" src="https://github.com/user-attachments/assets/302013ab-ebcd-4888-a4d0-1869f778b369" />
</div>

---

<div><p>Task Page:</p>
<img width="1236" height="805" alt="страница таск" src="https://github.com/user-attachments/assets/97f25808-11ee-40b5-a978-fa4f2e281197" />
</div>

---

<div><p>Failed Imports:</p>
<img width="1288" height="862" alt="ошибки при импорте" src="https://github.com/user-attachments/assets/fba18adb-6483-428c-bb9e-c2ba46975156" />
</div>

---

<div><p>Projects Page:</p>
<img width="1622" height="932" alt="страница с проектами" src="https://github.com/user-attachments/assets/c71b6526-58ee-4379-b5e5-7703165e6b19" />
</div>

---

<div><p>Modal Window for payment details:</p>
<img width="1268" height="838" alt="модальное окно" src="https://github.com/user-attachments/assets/a356689b-83b2-4be7-8283-d6327ad0da8f" />
</div>

---
