# Campus Maintenance System (CMRWAS) - XAMPP & MySQL Setup Guide

Ang sistemang ito ay ganap nang nakakabit sa **MySQL Database** gamit ang **XAMPP (Apache + PHP + MySQL)**.

---

## 📁 Direktoryo ng Proyekto
- **Live XAMPP Folder:** `C:\xampp\htdocs\cmrwas\`
- **Project Scratch Folder:** `C:\Users\MRI-USER\.gemini\antigravity\scratch\cmrwas\`
- **Local URL:** [http://localhost/cmrwas/](http://localhost/cmrwas/)

---

## 🗄️ Structure ng Database (`cmrwas_db`)

Ang database ay may 5 pangunahing tables at 2 views:

1. **`users`** - Naglalaman ng mga account para sa Administrator at regular User (na may hashed/plaintext check, role, at status).
2. **`technicians`** - Listahan ng maintenance technicians, specializations, contacts, at status (`Available`, `Busy`, `Inactive`).
3. **`equipment`** - Listahan ng campus equipment/assets, locations, condition (`Good`, `Needs Repair`, `Damaged`), at status (`Operational`, `Under Maintenance`).
4. **`maintenance_requests`** - Lahat ng service requests na may auto-generated IDs (`MR-001`, `MR-002`, ...), category, location, priority, description, status, at assigned technician ID.
5. **`transaction_history`** - Audit trail log ng lahat ng actions (pag-create ng request, pag-apruba, pag-update, pagdagdag ng technician/equipment).
6. **`request_details` (View)** - Auto-join ng requests kasama ang pangalan ng technician at requester.
7. **`dashboard_summary` (View)** - Mabilisang bilang ng total, pending, in-progress, at completed requests.

---

## 🔑 Default Login Credentials

| Role | Username | Password | Access Level |
|---|---|---|---|
| **Administrator** | `admin` | `admin123` | Buong access (Dashboard, Requests, Technicians, Equipment, Reports, History, Actions) |
| **Regular User** | `juan` | `user123` | User access (Dashboard, My Maintenance Requests, Create New Request) |

*Maaari ka ring mag-click ng **"Create Account"** sa login page para gumawa ng bagong User account.*

---

## 🚀 Paano Patakbuhin at Gamitin sa XAMPP

1. **Buksan ang XAMPP Control Panel:**
   - I-start ang **Apache** (tiyaking kulay green ang status).
   - I-start ang **MySQL** (tiyaking kulay green ang status).

2. **I-check o I-import ang Database sa phpMyAdmin (Kung bago ang setup):**
   - Buksan ang browser at pumunta sa [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Kung wala pa ang `cmrwas_db`, i-click ang **New** at ilagay ang database name: `cmrwas_db`.
   - Pumunta sa tab na **Import**, piliin ang file na `cmrwas_db.sql` na nasa folder, at i-click ang **Go**.
   *(Kasalukuyang naka-import na at gumagana na ito sa iyong XAMPP MySQL)*.

3. **Buksan ang Web App:**
   - Buksan ang iyong browser (Chrome, Edge, Firefox, atbp.).
   - I-type ang URL: **[http://localhost/cmrwas/](http://localhost/cmrwas/)**

---

## ⚙️ Mga Files at Papel Nito

- **`index.php`**: Ang kumpletong frontend user interface na may modernong styling, responsive sidebar, modals, status badges, at asynchronous JavaScript (`fetch()`) na direktang nakikipag-usap sa backend.
- **`api.php`**: Ang REST JSON API backend sa PHP. Pinamamahalaan nito ang authentication, sessions, role checking, CRUD operations, transactions, at reports.
- **`db.php`**: PDO connection helper para sa secure na koneksyon sa MySQL sa XAMPP (`localhost`, `root`, walang password).
- **`cmrwas_db.sql`**: Ang kumpletong SQL script para sa database structure at initial seed data.
