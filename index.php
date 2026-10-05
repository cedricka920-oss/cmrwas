<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Campus Maintenance System - CMRWAS</title>

<style>
/* =========================================================
   GENERAL
========================================================= */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #f3f4f6;
    color: #333;
}

button {
    font-family: Arial, sans-serif;
}

/* =========================================================
   LOGIN / REGISTER
========================================================= */
#loginPage,
#createAccountPage {
    min-height: 100vh;
    background: #f3f4f6;
}

.header {
    background: #23439d;
    color: white;
    padding: 18px 25px;
    font-size: 21px;
    font-weight: bold;
}

.login-container {
    min-height: calc(100vh - 65px);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 55px;
}

.login-box {
    background: white;
    width: 450px;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(0,0,0,.12);
}

.login-box h1 {
    text-align: center;
    color: #23439d;
    font-size: 25px;
    margin-bottom: 10px;
}

.subtitle {
    text-align: center;
    color: #777;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 7px;
}

input,
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 5px;
    outline: none;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2563eb;
}

textarea {
    resize: vertical;
    min-height: 90px;
}

.login-button {
    width: 100%;
    background: #2949b5;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
}

.login-button:hover {
    background: #1e3a8a;
}

.login-error,
.register-error {
    color: #dc2626;
    text-align: center;
    margin-top: 15px;
    display: none;
}

.success-message {
    color: #16a34a;
    text-align: center;
    margin-top: 15px;
    display: none;
}

.create-account-link {
    text-align: center;
    margin-top: 18px;
    color: #666;
    font-size: 14px;
}

.create-account-link button,
.back-login button {
    background: none;
    border: none;
    color: #2949b5;
    font-weight: bold;
    cursor: pointer;
    font-size: 14px;
}

.create-account-link button:hover,
.back-login button:hover {
    text-decoration: underline;
}

.back-login {
    text-align: center;
    margin-top: 18px;
}

.admin-note {
    border-top: 1px solid #ddd;
    margin-top: 25px;
    padding-top: 18px;
    text-align: center;
    color: #777;
    font-size: 13px;
}

/* =========================================================
   SYSTEM
========================================================= */
#systemPage {
    display: none;
    min-height: 100vh;
}

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 245px;
    height: 100vh;
    background: #172554;
    color: white;
    padding: 20px 15px;
    overflow-y: auto;
}

.logo {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    padding-bottom: 25px;
    border-bottom: 1px solid rgba(255,255,255,.15);
    margin-bottom: 20px;
}

.logo small {
    font-size: 11px;
}

.menu-title {
    color: #94a3b8;
    font-size: 12px;
    margin: 15px 10px 8px;
    text-transform: uppercase;
}

.sidebar button {
    width: 100%;
    border: none;
    background: transparent;
    color: white;
    text-align: left;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 5px;
    font-size: 14px;
}

.sidebar button:hover,
.sidebar button.active {
    background: #2949b5;
}

.logout-button {
    margin-top: 30px;
    background: #dc2626 !important;
}

.logout-button:hover {
    background: #b91c1c !important;
}

.main {
    margin-left: 245px;
    min-height: 100vh;
}

.topbar {
    background: white;
    padding: 18px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
}

.topbar h1 {
    font-size: 22px;
    color: #172554;
}

.admin-profile {
    display: flex;
    align-items: center;
    gap: 10px;
}

.admin-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #2949b5;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}

.admin-info small {
    display: block;
    color: #777;
}

.admin-info strong {
    font-size: 14px;
}

.content {
    padding: 25px;
}

.section {
    display: none;
}

.section.active {
    display: block;
}

/* =========================================================
   DASHBOARD
========================================================= */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 18px;
    margin-bottom: 25px;
}

.dashboard-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 5px solid #2949b5;
    box-shadow: 0 2px 7px rgba(0,0,0,.07);
}

.dashboard-card h4 {
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 10px;
}

.dashboard-card h2 {
    color: #172554;
    font-size: 28px;
}

.box {
    background: white;
    padding: 22px;
    border-radius: 8px;
    box-shadow: 0 2px 7px rgba(0,0,0,.06);
    margin-bottom: 20px;
}

.box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.box-header h2 {
    color: #172554;
    font-size: 19px;
}

/* =========================================================
   BUTTONS
========================================================= */
.btn {
    padding: 9px 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 13px;
    font-weight: bold;
    display: inline-block;
}

.btn-primary {
    background: #2949b5;
    color: white;
}

.btn-success {
    background: #16a34a;
    color: white;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-warning {
    background: #d97706;
    color: white;
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn:hover {
    opacity: .9;
}

/* =========================================================
   TABLE
========================================================= */
.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #eff6ff;
    color: #172554;
    padding: 12px;
    text-align: left;
    font-size: 13px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 13px;
}

tr:hover {
    background: #f8fafc;
}

/* =========================================================
   STATUS
========================================================= */
.status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #dcfce7;
    color: #166534;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.status-progress {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-available {
    background: #dcfce7;
    color: #166534;
}

.status-busy {
    background: #fef3c7;
    color: #92400e;
}

/* =========================================================
   TOOLBAR
========================================================= */
.toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.search-box {
    width: 280px;
}

.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

/* =========================================================
   MODAL
========================================================= */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    width: 500px;
    max-width: 95%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 25px;
    border-radius: 10px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.modal-header h2 {
    color: #172554;
}

.close {
    font-size: 25px;
    cursor: pointer;
    color: #777;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 20px;
}

/* =========================================================
   REPORTS
========================================================= */
.report-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 18px;
}

.report-card {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    padding: 20px;
    border-radius: 8px;
}

.report-card h3 {
    color: #172554;
    margin-bottom: 10px;
}

.report-number {
    font-size: 30px;
    font-weight: bold;
}

/* =========================================================
   NOTIFICATION
========================================================= */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #16a34a;
    color: white;
    padding: 14px 20px;
    border-radius: 6px;
    display: none;
    z-index: 2000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* =========================================================
   USER DASHBOARD
========================================================= */
.user-welcome {
    background: #2949b5;
    color: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.user-welcome h2 {
    margin-bottom: 8px;
}

.user-welcome p {
    opacity: .9;
}

/* =========================================================
   MOBILE
========================================================= */
@media(max-width:900px) {
    .sidebar {
        width: 200px;
    }
    .main {
        margin-left: 200px;
    }
    .dashboard-cards {
        grid-template-columns: repeat(2,1fr);
    }
}

@media(max-width:650px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }
    .main {
        margin-left: 0;
    }
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
    .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .report-grid {
        grid-template-columns: 1fr;
    }
    .login-box {
        width: 92%;
    }
    .content {
        padding: 15px;
    }
}
</style>
</head>

<body>

<!-- =========================================================
     LOGIN PAGE
========================================================= -->
<div id="loginPage">
    <div class="header">
        Campus Maintenance System (CMRWAS)
    </div>

    <div class="login-container">
        <div class="login-box">
            <h1>CMRWAS Login</h1>
            <p class="subtitle">
                Administrator / User Authentication
            </p>

            <div class="form-group">
                <label>Username or Email</label>
                <input
                    type="text"
                    id="loginUsername"
                    placeholder="Enter username or email"
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    id="loginPassword"
                    placeholder="Enter password"
                >
            </div>

            <button
                class="login-button"
                id="loginBtn"
                onclick="login()"
            >
                Log In
            </button>

            <div
                class="login-error"
                id="loginError"
            >
                Invalid username or password.
            </div>

            <div class="create-account-link">
                Don't have an account?
                <button onclick="showCreateAccount()">
                    Create Account
                </button>
            </div>

            <div class="admin-note">
                Default: <strong>admin</strong> / <strong>admin123</strong> (Admin) &nbsp;|&nbsp; <strong>juan</strong> / <strong>user123</strong> (User)
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     CREATE ACCOUNT PAGE
========================================================= -->
<div id="createAccountPage" style="display:none;">
    <div class="header">
        Campus Maintenance System (CMRWAS)
    </div>

    <div class="login-container">
        <div class="login-box">
            <h1>Create Account</h1>
            <p class="subtitle">
                Create your User Account
            </p>

            <div class="form-group">
                <label>Full Name</label>
                <input
                    type="text"
                    id="registerName"
                    placeholder="Enter full name"
                >
            </div>

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    id="registerUsername"
                    placeholder="Create username"
                >
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input
                    type="email"
                    id="registerEmail"
                    placeholder="Enter email address"
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    id="registerPassword"
                    placeholder="Create password"
                >
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input
                    type="password"
                    id="confirmPassword"
                    placeholder="Confirm password"
                >
            </div>

            <button
                class="login-button"
                id="registerBtn"
                onclick="createAccount()"
            >
                Create Account
            </button>

            <div
                class="register-error"
                id="registerError"
            ></div>

            <div
                class="success-message"
                id="registerSuccess"
            ></div>

            <div class="back-login">
                <button onclick="showLogin()">
                    ← Back to Login
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MAIN SYSTEM
========================================================= -->
<div id="systemPage">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            CMRWAS
            <br>
            <small>Campus Maintenance</small>
        </div>

        <div class="menu-title">Main Menu</div>

        <button
            class="active"
            id="dashboardMenu"
            onclick="showSection('dashboard', this)"
        >
            📊 Dashboard
        </button>

        <button
            id="requestsMenu"
            onclick="showSection('requests', this)"
        >
            🛠 Maintenance Requests
        </button>

        <button
            id="techniciansMenu"
            onclick="showSection('technicians', this)"
        >
            👷 Technicians
        </button>

        <button
            id="equipmentMenu"
            onclick="showSection('equipment', this)"
        >
            🔧 Equipment
        </button>

        <div class="menu-title" id="managementTitle">Management</div>

        <button
            id="reportsMenu"
            onclick="showSection('reports', this)"
        >
            📈 Reports
        </button>

        <button
            id="historyMenu"
            onclick="showSection('history', this)"
        >
            🕒 Transaction History
        </button>

        <button
            class="logout-button"
            onclick="logout()"
        >
            🚪 Logout
        </button>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <h1 id="pageTitle">Dashboard</h1>

            <div class="admin-profile">
                <div class="admin-avatar" id="adminAvatar">A</div>
                <div class="admin-info">
                    <strong id="adminName">Administrator</strong>
                    <small id="adminUsername">@admin</small>
                </div>
            </div>
        </div>

        <div class="content">

            <!-- =================================================
                 DASHBOARD
            ================================================= -->
            <section id="dashboard" class="section active">
                <div class="user-welcome" id="userWelcome" style="display:none;">
                    <h2>Welcome to CMRWAS!</h2>
                    <p>Submit and monitor your campus maintenance requests in real-time.</p>
                </div>

                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <h4>Total Requests</h4>
                        <h2 id="totalRequests">0</h2>
                    </div>

                    <div class="dashboard-card">
                        <h4>Pending Requests</h4>
                        <h2 id="pendingRequests">0</h2>
                    </div>

                    <div class="dashboard-card">
                        <h4>In Progress</h4>
                        <h2 id="progressRequests">0</h2>
                    </div>

                    <div class="dashboard-card">
                        <h4>Completed</h4>
                        <h2 id="completedRequests">0</h2>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h2>Recent Maintenance Requests</h2>
                        <button class="btn btn-primary" onclick="showSection('requests')">
                            View All
                        </button>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Requester</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="dashboardTable">
                                <tr><td colspan="6" style="text-align:center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- =================================================
                 REQUESTS
            ================================================= -->
            <section id="requests" class="section">
                <div class="box">
                    <div class="box-header">
                        <h2 id="requestPageTitle">Maintenance Requests</h2>
                        <button class="btn btn-primary" onclick="openRequestModal()">
                            + New Request
                        </button>
                    </div>

                    <div class="toolbar">
                        <input
                            class="search-box"
                            type="text"
                            id="searchRequest"
                            placeholder="Search requests..."
                            onkeyup="filterRequests()"
                        >

                        <select
                            id="statusFilter"
                            onchange="filterRequests()"
                            style="width:180px"
                        >
                            <option value="All">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Rejected">Rejected</option>
                        </select>

                        <select
                            id="priorityFilter"
                            onchange="filterRequests()"
                            style="width:160px"
                        >
                            <option value="All">All Priority</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Requester</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Technician</th>
                                    <th id="actionHeader">Action</th>
                                </tr>
                            </thead>
                            <tbody id="requestTable">
                                <tr><td colspan="8" style="text-align:center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- =================================================
                 TECHNICIANS
            ================================================= -->
            <section id="technicians" class="section">
                <div class="box">
                    <div class="box-header">
                        <h2>Technician Management</h2>
                        <button class="btn btn-primary" onclick="openTechnicianModal()">
                            + Add Technician
                        </button>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Specialization</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="technicianTable">
                                <tr><td colspan="5" style="text-align:center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- =================================================
                 EQUIPMENT
            ================================================= -->
            <section id="equipment" class="section">
                <div class="box">
                    <div class="box-header">
                        <h2>Equipment Management</h2>
                        <button class="btn btn-primary" onclick="openEquipmentModal()">
                            + Add Equipment
                        </button>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Equipment</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="equipmentTable">
                                <tr><td colspan="6" style="text-align:center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- =================================================
                 REPORTS
            ================================================= -->
            <section id="reports" class="section">
                <div class="box">
                    <div class="box-header">
                        <h2>Maintenance Reports</h2>
                        <button class="btn btn-primary" onclick="generateReport()">
                            Generate Summary Alert
                        </button>
                    </div>

                    <div class="report-grid">
                        <div class="report-card">
                            <h3>Total Requests</h3>
                            <div class="report-number" id="reportTotal">0</div>
                        </div>

                        <div class="report-card">
                            <h3>Completed</h3>
                            <div class="report-number" id="reportCompleted" style="color:#16a34a;">0</div>
                        </div>

                        <div class="report-card">
                            <h3>Pending</h3>
                            <div class="report-number" id="reportPending" style="color:#d97706;">0</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =================================================
                 HISTORY
            ================================================= -->
            <section id="history" class="section">
                <div class="box">
                    <div class="box-header">
                        <h2>Transaction History</h2>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Admin / User</th>
                                    <th>Action</th>
                                    <th>Request ID</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody id="historyTable">
                                <tr><td colspan="5" style="text-align:center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </main>
</div>

<!-- =========================================================
     REQUEST MODAL
========================================================= -->
<div class="modal" id="requestModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="requestModalTitle">New Maintenance Request</h2>
            <span class="close" onclick="closeRequestModal()">×</span>
        </div>

        <input type="hidden" id="editRequestId">

        <div class="form-group">
            <label>Requester Name</label>
            <input type="text" id="requester" placeholder="Enter requester name">
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" id="location" placeholder="e.g. Room 201">
        </div>

        <div class="form-group">
            <label>Maintenance Category</label>
            <select id="category">
                <option value="Electrical">Electrical</option>
                <option value="Plumbing">Plumbing</option>
                <option value="Furniture">Furniture</option>
                <option value="Cleaning">Cleaning</option>
                <option value="Air Conditioning">Air Conditioning</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Priority</label>
            <select id="priority">
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <div class="form-group" id="technicianSelectGroup" style="display:none;">
            <label>Assign Technician</label>
            <select id="assignedTechnician">
                <option value="">-- Unassigned --</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea id="description" placeholder="Describe the maintenance problem"></textarea>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeRequestModal()">Cancel</button>
            <button class="btn btn-primary" id="saveRequestBtn" onclick="saveRequest()">Save Request</button>
        </div>
    </div>
</div>

<!-- =========================================================
     TECHNICIAN MODAL
========================================================= -->
<div class="modal" id="technicianModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Technician</h2>
            <span class="close" onclick="closeTechnicianModal()">×</span>
        </div>

        <div class="form-group">
            <label>Technician Name</label>
            <input type="text" id="technicianName" placeholder="Enter technician name">
        </div>

        <div class="form-group">
            <label>Specialization</label>
            <select id="specialization">
                <option value="Electrical">Electrical</option>
                <option value="Plumbing">Plumbing</option>
                <option value="Carpentry">Carpentry</option>
                <option value="General Maintenance">General Maintenance</option>
            </select>
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" id="technicianContact" placeholder="09XXXXXXXXX">
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeTechnicianModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveTechnician()">Save</button>
        </div>
    </div>
</div>

<!-- =========================================================
     EQUIPMENT MODAL
========================================================= -->
<div class="modal" id="equipmentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Equipment</h2>
            <span class="close" onclick="closeEquipmentModal()">×</span>
        </div>

        <div class="form-group">
            <label>Equipment Name</label>
            <input type="text" id="equipmentName" placeholder="Enter equipment name">
        </div>

        <div class="form-group">
            <label>Category</label>
            <input type="text" id="equipmentCategory" placeholder="e.g. HVAC, Electrical">
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" id="equipmentLocation" placeholder="e.g. Room 305">
        </div>

        <div class="form-group">
            <label>Condition</label>
            <select id="equipmentCondition">
                <option value="Good">Good</option>
                <option value="Needs Repair">Needs Repair</option>
                <option value="Damaged">Damaged</option>
            </select>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeEquipmentModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveEquipment()">Save</button>
        </div>
    </div>
</div>

<!-- =========================================================
     NOTIFICATION
========================================================= -->
<div class="notification" id="notification"></div>

<!-- =========================================================
     FRONTEND JAVASCRIPT CONNECTED TO XAMPP PHP/MYSQL API
========================================================= -->
<script>
let currentUser = null;
let cachedRequests = [];
let cachedTechnicians = [];

/* =========================================================
   API HELPER
========================================================= */
async function apiCall(action, options = {}) {
    const url = 'api.php?action=' + encodeURIComponent(action);
    const config = {
        headers: {
            'Accept': 'application/json',
            ...(options.body ? { 'Content-Type': 'application/json' } : {})
        },
        ...options
    };

    try {
        const response = await fetch(url, config);
        const data = await response.json();
        return data;
    } catch (err) {
        console.error('API Error:', err);
        return { success: false, error: 'Network error or server unavailable.' };
    }
}

/* =========================================================
   LOGIN
========================================================= */
async function login() {
    const username = document.getElementById("loginUsername").value.trim();
    const password = document.getElementById("loginPassword").value;
    const errorEl = document.getElementById("loginError");

    errorEl.style.display = "none";

    if (!username || !password) {
        errorEl.innerText = "Please enter username and password.";
        errorEl.style.display = "block";
        return;
    }

    const btn = document.getElementById("loginBtn");
    btn.disabled = true;
    btn.innerText = "Logging in...";

    const res = await apiCall('login', {
        method: 'POST',
        body: JSON.stringify({ username, password })
    });

    btn.disabled = false;
    btn.innerText = "Log In";

    if (!res.success) {
        errorEl.innerText = res.error || "Invalid username or password.";
        errorEl.style.display = "block";
        return;
    }

    currentUser = res.user;

    document.getElementById("loginPage").style.display = "none";
    document.getElementById("createAccountPage").style.display = "none";
    document.getElementById("systemPage").style.display = "block";

    updateUserProfile(currentUser);
    setupUserRole(currentUser);
    loadAllData();

    showNotification("Welcome, " + currentUser.name + "!");
}

/* =========================================================
   CREATE ACCOUNT (REGISTER)
========================================================= */
async function createAccount() {
    const name = document.getElementById("registerName").value.trim();
    const username = document.getElementById("registerUsername").value.trim();
    const email = document.getElementById("registerEmail").value.trim();
    const password = document.getElementById("registerPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    const error = document.getElementById("registerError");
    const success = document.getElementById("registerSuccess");

    error.style.display = "none";
    success.style.display = "none";

    if (!name || !username || !email || !password || !confirmPassword) {
        error.innerText = "Please complete all fields.";
        error.style.display = "block";
        return;
    }

    if (username.length < 4) {
        error.innerText = "Username must be at least 4 characters.";
        error.style.display = "block";
        return;
    }

    if (password.length < 6) {
        error.innerText = "Password must be at least 6 characters.";
        error.style.display = "block";
        return;
    }

    if (password !== confirmPassword) {
        error.innerText = "Passwords do not match.";
        error.style.display = "block";
        return;
    }

    if (!email.includes("@")) {
        error.innerText = "Please enter a valid email address.";
        error.style.display = "block";
        return;
    }

    const btn = document.getElementById("registerBtn");
    btn.disabled = true;
    btn.innerText = "Creating account...";

    const res = await apiCall('register', {
        method: 'POST',
        body: JSON.stringify({ name, username, email, password })
    });

    btn.disabled = false;
    btn.innerText = "Create Account";

    if (!res.success) {
        error.innerText = res.error || "Failed to create account.";
        error.style.display = "block";
        return;
    }

    success.innerText = "User account created successfully in MySQL database!";
    success.style.display = "block";

    document.getElementById("registerName").value = "";
    document.getElementById("registerUsername").value = "";
    document.getElementById("registerEmail").value = "";
    document.getElementById("registerPassword").value = "";
    document.getElementById("confirmPassword").value = "";

    setTimeout(function() {
        showLogin();
        document.getElementById("loginUsername").value = username;
        document.getElementById("loginPassword").value = "";
    }, 1500);
}

/* =========================================================
   LOGOUT
========================================================= */
async function logout() {
    await apiCall('logout');
    currentUser = null;

    document.getElementById("systemPage").style.display = "none";
    document.getElementById("createAccountPage").style.display = "none";
    document.getElementById("loginPage").style.display = "block";

    document.getElementById("loginUsername").value = "";
    document.getElementById("loginPassword").value = "";
    document.getElementById("loginError").style.display = "none";
}

/* =========================================================
   NAVIGATION / SWITCH PAGES
========================================================= */
function showCreateAccount() {
    document.getElementById("loginPage").style.display = "none";
    document.getElementById("createAccountPage").style.display = "block";
    document.getElementById("registerError").style.display = "none";
    document.getElementById("registerSuccess").style.display = "none";
}

function showLogin() {
    document.getElementById("createAccountPage").style.display = "none";
    document.getElementById("loginPage").style.display = "block";
    document.getElementById("registerError").style.display = "none";
    document.getElementById("registerSuccess").style.display = "none";
}

function showSection(sectionID, button = null) {
    if (currentUser && currentUser.role === "User") {
        const allowedSections = ["dashboard", "requests"];
        if (!allowedSections.includes(sectionID)) {
            showNotification("You do not have permission to access this page.");
            return;
        }
    }

    document.querySelectorAll(".section").forEach(function(section) {
        section.classList.remove("active");
    });

    const targetSection = document.getElementById(sectionID);
    if (targetSection) targetSection.classList.add("active");

    document.querySelectorAll(".sidebar button").forEach(function(btn) {
        btn.classList.remove("active");
    });

    if (button) {
        button.classList.add("active");
    } else {
        const matchingBtn = document.getElementById(sectionID + "Menu");
        if (matchingBtn) matchingBtn.classList.add("active");
    }

    const titles = {
        dashboard: "Dashboard",
        requests: currentUser && currentUser.role === "User" ? "My Maintenance Requests" : "Maintenance Requests",
        technicians: "Technician Management",
        equipment: "Equipment Management",
        reports: "Maintenance Reports",
        history: "Transaction History"
    };

    document.getElementById("pageTitle").innerText = titles[sectionID] || "Dashboard";

    loadAllData();
}

/* =========================================================
   PROFILE & ROLES
========================================================= */
function updateUserProfile(account) {
    document.getElementById("adminName").innerText = account.name;
    document.getElementById("adminUsername").innerText = "@" + account.username;
    document.getElementById("adminAvatar").innerText = account.name.charAt(0).toUpperCase();
}

function setupUserRole(account) {
    const dashboardMenu = document.getElementById("dashboardMenu");
    const requestsMenu = document.getElementById("requestsMenu");
    const techniciansMenu = document.getElementById("techniciansMenu");
    const equipmentMenu = document.getElementById("equipmentMenu");
    const reportsMenu = document.getElementById("reportsMenu");
    const historyMenu = document.getElementById("historyMenu");
    const managementTitle = document.getElementById("managementTitle");
    const userWelcome = document.getElementById("userWelcome");
    const actionHeader = document.getElementById("actionHeader");

    if (account.role === "Administrator") {
        dashboardMenu.style.display = "block";
        requestsMenu.style.display = "block";
        techniciansMenu.style.display = "block";
        equipmentMenu.style.display = "block";
        reportsMenu.style.display = "block";
        historyMenu.style.display = "block";
        managementTitle.style.display = "block";
        userWelcome.style.display = "none";
        actionHeader.style.display = "";

        requestsMenu.innerHTML = "🛠 Maintenance Requests";
        document.getElementById("requestPageTitle").innerText = "Maintenance Requests";
    } else {
        dashboardMenu.style.display = "block";
        requestsMenu.style.display = "block";
        techniciansMenu.style.display = "none";
        equipmentMenu.style.display = "none";
        reportsMenu.style.display = "none";
        historyMenu.style.display = "none";
        managementTitle.style.display = "none";
        userWelcome.style.display = "block";
        actionHeader.style.display = "none";

        requestsMenu.innerHTML = "🛠 My Maintenance Requests";
        document.getElementById("requestPageTitle").innerText = "My Maintenance Requests";
    }
}

/* =========================================================
   DATA LOADERS
========================================================= */
async function loadAllData() {
    updateDashboard();
    loadRequests();

    if (currentUser && currentUser.role === "Administrator") {
        loadTechnicians();
        loadEquipment();
        loadHistory();
        updateReports();
    }
}

/* =========================================================
   DASHBOARD
========================================================= */
async function updateDashboard() {
    const res = await apiCall('get_dashboard');
    if (!res.success) return;

    const stats = res.stats;
    document.getElementById("totalRequests").innerText = stats.total;
    document.getElementById("pendingRequests").innerText = stats.pending;
    document.getElementById("progressRequests").innerText = stats.progress;
    document.getElementById("completedRequests").innerText = stats.completed;

    const table = document.getElementById("dashboardTable");
    table.innerHTML = "";

    if (!res.recent || res.recent.length === 0) {
        table.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;">No recent requests found.</td></tr>`;
        return;
    }

    res.recent.forEach(function(req) {
        table.innerHTML += `
            <tr>
                <td><strong>${req.request_code}</strong></td>
                <td>${req.requester_name}</td>
                <td>${req.location}</td>
                <td>${req.category}</td>
                <td>${req.priority}</td>
                <td>${statusBadge(req.status)}</td>
            </tr>
        `;
    });
}

/* =========================================================
   STATUS BADGE
========================================================= */
function statusBadge(status) {
    let className = "";
    if (status === "Pending") className = "status-pending";
    else if (status === "Approved") className = "status-approved";
    else if (status === "Rejected") className = "status-rejected";
    else if (status === "In Progress") className = "status-progress";
    else if (status === "Completed") className = "status-completed";

    return `<span class="status ${className}">${status}</span>`;
}

/* =========================================================
   REQUESTS TABLE & FILTER
========================================================= */
let filterDebounce = null;
function filterRequests() {
    clearTimeout(filterDebounce);
    filterDebounce = setTimeout(loadRequests, 250);
}

async function loadRequests() {
    const table = document.getElementById("requestTable");
    if (!table) return;

    const search = document.getElementById("searchRequest")?.value || "";
    const status = document.getElementById("statusFilter")?.value || "All";
    const priority = document.getElementById("priorityFilter")?.value || "All";

    const query = `get_requests&search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}&priority=${encodeURIComponent(priority)}`;
    const res = await apiCall(query);

    if (!res.success) {
        table.innerHTML = `<tr><td colspan="8" style="text-align:center;color:#dc2626;">Failed to load requests.</td></tr>`;
        return;
    }

    cachedRequests = res.requests;
    table.innerHTML = "";

    const isAdmin = (currentUser && currentUser.role === "Administrator");

    if (cachedRequests.length === 0) {
        table.innerHTML = `<tr><td colspan="8" style="text-align:center;color:#888;">No maintenance requests found.</td></tr>`;
        return;
    }

    cachedRequests.forEach(function(request) {
        let actionButtons = "";

        if (isAdmin) {
            actionButtons = `
                <button class="btn btn-secondary" onclick="editRequest('${request.id}')">Edit</button>
            `;

            if (request.status === "Pending") {
                actionButtons += `
                    <button class="btn btn-success" onclick="approveRequest('${request.id}')">Approve</button>
                    <button class="btn btn-danger" onclick="rejectRequest('${request.id}')">Reject</button>
                `;
            }

            if (request.status === "Approved") {
                actionButtons += `
                    <button class="btn btn-warning" onclick="startRequest('${request.id}')">Start</button>
                `;
            }

            if (request.status === "In Progress") {
                actionButtons += `
                    <button class="btn btn-success" onclick="completeRequest('${request.id}')">Complete</button>
                `;
            }

            actionButtons += `
                <button class="btn btn-danger" onclick="deleteRequest('${request.id}')">Delete</button>
            `;
        }

        table.innerHTML += `
            <tr>
                <td><strong>${request.request_code}</strong></td>
                <td>${request.requester_name}</td>
                <td>${request.location}</td>
                <td>${request.category}</td>
                <td>${request.priority}</td>
                <td>${statusBadge(request.status)}</td>
                <td>${request.technician}</td>
                ${isAdmin ? `<td><div class="action-buttons">${actionButtons}</div></td>` : ''}
            </tr>
        `;
    });
}

/* =========================================================
   REQUEST MODAL (ADD / EDIT)
========================================================= */
async function openRequestModal() {
    document.getElementById("requestModal").style.display = "flex";
    document.getElementById("requestModalTitle").innerText = "New Maintenance Request";
    document.getElementById("editRequestId").value = "";

    const techGroup = document.getElementById("technicianSelectGroup");
    techGroup.style.display = "none";

    if (currentUser && currentUser.role === "User") {
        document.getElementById("requester").value = currentUser.name;
        document.getElementById("requester").readOnly = true;
    } else {
        document.getElementById("requester").value = "";
        document.getElementById("requester").readOnly = false;
    }

    document.getElementById("location").value = "";
    document.getElementById("category").value = "Electrical";
    document.getElementById("priority").value = "Medium";
    document.getElementById("description").value = "";
}

function closeRequestModal() {
    document.getElementById("requestModal").style.display = "none";
}

async function editRequest(id) {
    if (!currentUser || currentUser.role !== "Administrator") {
        showNotification("Only administrators can edit requests.");
        return;
    }

    const request = cachedRequests.find(r => String(r.id) === String(id) || r.request_code === id);
    if (!request) return;

    // Load technicians into select
    await populateTechnicianDropdown(request.technician_id);

    document.getElementById("requestModal").style.display = "flex";
    document.getElementById("requestModalTitle").innerText = "Edit Maintenance Request (" + request.request_code + ")";
    document.getElementById("editRequestId").value = request.id;

    document.getElementById("requester").value = request.requester_name;
    document.getElementById("requester").readOnly = false;
    document.getElementById("location").value = request.location;
    document.getElementById("category").value = request.category;
    document.getElementById("priority").value = request.priority;
    document.getElementById("description").value = request.description;

    document.getElementById("technicianSelectGroup").style.display = "block";
}

async function populateTechnicianDropdown(selectedTechId = null) {
    const select = document.getElementById("assignedTechnician");
    select.innerHTML = '<option value="">-- Unassigned --</option>';

    if (cachedTechnicians.length === 0) {
        const res = await apiCall('get_technicians');
        if (res.success) cachedTechnicians = res.technicians;
    }

    cachedTechnicians.forEach(t => {
        const isSel = (String(t.id) === String(selectedTechId)) ? 'selected' : '';
        select.innerHTML += `<option value="${t.id}" ${isSel}>${t.name} (${t.specialization}) - ${t.status}</option>`;
    });
}

/* =========================================================
   SAVE REQUEST
========================================================= */
async function saveRequest() {
    const requester = document.getElementById("requester").value.trim();
    const location = document.getElementById("location").value.trim();
    const category = document.getElementById("category").value;
    const priority = document.getElementById("priority").value;
    const description = document.getElementById("description").value.trim();
    const editRequestId = document.getElementById("editRequestId").value;
    const technician_id = document.getElementById("assignedTechnician")?.value || null;

    if (!requester || !location || !description) {
        alert("Please complete all required fields.");
        return;
    }

    const btn = document.getElementById("saveRequestBtn");
    btn.disabled = true;
    btn.innerText = "Saving...";

    const res = await apiCall('save_request', {
        method: 'POST',
        body: JSON.stringify({
            editRequestId,
            requester,
            location,
            category,
            priority,
            description,
            technician_id
        })
    });

    btn.disabled = false;
    btn.innerText = "Save Request";

    if (!res.success) {
        alert(res.error || "Failed to save request.");
        return;
    }

    closeRequestModal();
    loadAllData();
    showNotification(res.message || "Maintenance request saved successfully.");
}

/* =========================================================
   STATUS UPDATES
========================================================= */
async function updateStatus(id, status) {
    const res = await apiCall('update_status', {
        method: 'POST',
        body: JSON.stringify({ id, status })
    });

    if (res.success) {
        showNotification(res.message);
        loadAllData();
    } else {
        alert(res.error || "Failed to update status.");
    }
}

function approveRequest(id) { updateStatus(id, "Approved"); }
function rejectRequest(id) { updateStatus(id, "Rejected"); }
function startRequest(id) { updateStatus(id, "In Progress"); }
function completeRequest(id) { updateStatus(id, "Completed"); }

async function deleteRequest(id) {
    if (!confirm("Are you sure you want to delete this maintenance request?")) return;

    const res = await apiCall('delete_request', {
        method: 'POST',
        body: JSON.stringify({ id })
    });

    if (res.success) {
        showNotification("Request deleted successfully.");
        loadAllData();
    } else {
        alert(res.error || "Failed to delete request.");
    }
}

/* =========================================================
   TECHNICIANS
========================================================= */
async function loadTechnicians() {
    const table = document.getElementById("technicianTable");
    if (!table) return;

    const res = await apiCall('get_technicians');
    if (!res.success) return;

    cachedTechnicians = res.technicians;
    table.innerHTML = "";

    if (cachedTechnicians.length === 0) {
        table.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#888;">No technicians found.</td></tr>`;
        return;
    }

    cachedTechnicians.forEach(t => {
        const statusClass = (t.status === "Available") ? "status-available" : "status-busy";
        table.innerHTML += `
            <tr>
                <td><strong>${t.technician_code}</strong></td>
                <td>${t.name}</td>
                <td>${t.specialization}</td>
                <td>${t.contact}</td>
                <td><span class="status ${statusClass}">${t.status}</span></td>
            </tr>
        `;
    });
}

function openTechnicianModal() {
    document.getElementById("technicianModal").style.display = "flex";
    document.getElementById("technicianName").value = "";
    document.getElementById("technicianContact").value = "";
}

function closeTechnicianModal() {
    document.getElementById("technicianModal").style.display = "none";
}

async function saveTechnician() {
    const name = document.getElementById("technicianName").value.trim();
    const specialization = document.getElementById("specialization").value;
    const contact = document.getElementById("technicianContact").value.trim();

    if (!name || !contact) {
        alert("Please complete all fields.");
        return;
    }

    const res = await apiCall('save_technician', {
        method: 'POST',
        body: JSON.stringify({ name, specialization, contact })
    });

    if (res.success) {
        closeTechnicianModal();
        loadTechnicians();
        showNotification("Technician added successfully.");
    } else {
        alert(res.error || "Failed to add technician.");
    }
}

/* =========================================================
   EQUIPMENT
========================================================= */
async function loadEquipment() {
    const table = document.getElementById("equipmentTable");
    if (!table) return;

    const res = await apiCall('get_equipment');
    if (!res.success) return;

    table.innerHTML = "";

    if (!res.equipment || res.equipment.length === 0) {
        table.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;">No equipment found.</td></tr>`;
        return;
    }

    res.equipment.forEach(item => {
        table.innerHTML += `
            <tr>
                <td><strong>${item.equipment_code}</strong></td>
                <td>${item.equipment_name}</td>
                <td>${item.category}</td>
                <td>${item.location}</td>
                <td>${item.condition_status}</td>
                <td>${item.equipment_status}</td>
            </tr>
        `;
    });
}

function openEquipmentModal() {
    document.getElementById("equipmentModal").style.display = "flex";
    document.getElementById("equipmentName").value = "";
    document.getElementById("equipmentCategory").value = "";
    document.getElementById("equipmentLocation").value = "";
}

function closeEquipmentModal() {
    document.getElementById("equipmentModal").style.display = "none";
}

async function saveEquipment() {
    const name = document.getElementById("equipmentName").value.trim();
    const category = document.getElementById("equipmentCategory").value.trim();
    const location = document.getElementById("equipmentLocation").value.trim();
    const condition = document.getElementById("equipmentCondition").value;

    if (!name || !category || !location) {
        alert("Please complete all fields.");
        return;
    }

    const res = await apiCall('save_equipment', {
        method: 'POST',
        body: JSON.stringify({ name, category, location, condition })
    });

    if (res.success) {
        closeEquipmentModal();
        loadEquipment();
        showNotification("Equipment added successfully.");
    } else {
        alert(res.error || "Failed to add equipment.");
    }
}

/* =========================================================
   TRANSACTION HISTORY
========================================================= */
async function loadHistory() {
    const table = document.getElementById("historyTable");
    if (!table) return;

    const res = await apiCall('get_history');
    if (!res.success) return;

    table.innerHTML = "";

    if (!res.history || res.history.length === 0) {
        table.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#888;">No transaction history yet.</td></tr>`;
        return;
    }

    res.history.forEach(item => {
        table.innerHTML += `
            <tr>
                <td>${item.date}</td>
                <td>${item.admin}</td>
                <td><strong>${item.action}</strong></td>
                <td>${item.requestID}</td>
                <td>${item.details}</td>
            </tr>
        `;
    });
}

/* =========================================================
   REPORTS
========================================================= */
async function updateReports() {
    const res = await apiCall('get_reports');
    if (!res.success) return;

    document.getElementById("reportTotal").innerText = res.report.total;
    document.getElementById("reportCompleted").innerText = res.report.completed;
    document.getElementById("reportPending").innerText = res.report.pending;
}

async function generateReport() {
    const res = await apiCall('get_reports');
    if (!res.success) return;

    const r = res.report;
    alert(
        "CAMPUS MAINTENANCE REPORT\n\n" +
        "Total Requests: " + r.total + "\n" +
        "Pending: " + r.pending + "\n" +
        "In Progress: " + r.progress + "\n" +
        "Completed: " + r.completed
    );
}

/* =========================================================
   NOTIFICATION TOAST
========================================================= */
function showNotification(message) {
    const notification = document.getElementById("notification");
    notification.innerText = message;
    notification.style.display = "block";
    setTimeout(function() {
        notification.style.display = "none";
    }, 3000);
}

/* =========================================================
   ENTER KEY LOGIN
========================================================= */
document.getElementById("loginPassword").addEventListener("keypress", function(event) {
    if (event.key === "Enter") login();
});

/* =========================================================
   INITIALIZATION & AUTO-LOGIN CHECK
========================================================= */
(async function init() {
    const res = await apiCall('check_session');
    if (res.success && res.user) {
        currentUser = res.user;
        document.getElementById("loginPage").style.display = "none";
        document.getElementById("createAccountPage").style.display = "none";
        document.getElementById("systemPage").style.display = "block";
        updateUserProfile(currentUser);
        setupUserRole(currentUser);
        loadAllData();
    }
})();
</script>

</body>
</html>
