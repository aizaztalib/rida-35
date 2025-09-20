<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "slmsdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $adminId = $_SESSION['id']; // Assuming session stores admin ID

    $result = $conn->query("SELECT Password FROM admin WHERE id = $adminId");
    $row = $result->fetch_assoc();

    if ($row && $row['Password'] == $current) {
        if ($new === $confirm) {
            $conn->query("UPDATE admin SET Password = '$new' WHERE id = $adminId");
            $msg = "<span style='color: green;'>✅ Password changed successfully.</span>";
        } else {
            $msg = "<span style='color: red;'>❌ New passwords do not match.</span>";
        }
    } else {
        $msg = "<span style='color: red;'>❌ Current password is incorrect.</span>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password | SLMS</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background-color: #f4faff; }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #1e3c72, #2a5298);
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
        }
        .sidebar h2 { text-align: center; margin-bottom: 20px; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li { padding: 10px 20px; }
        .sidebar ul li a { color: white; text-decoration: none; display: block; }
        .sidebar ul li a:hover { background-color: rgba(255,255,255,0.1); border-radius: 4px; }
        ul ul { display: none; padding-left: 15px; }
        ul ul li { padding: 6px 0; }

        /* Main content */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 { margin: 0; color: #2a5298; }

        /* Form card */
        form {
            max-width: 500px;
            background: white;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
        }
        input[type="password"]:focus {
            border-color: #2a5298;
        }
        button {
            margin-top: 20px;
            background-color: #2a5298;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            width: 100%;
        }
        button:hover {
            background-color: #1e3c72;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li>
            <a href="javascript:void(0);" onclick="toggleStudentMenu()">🧑‍🎓 Students ▼</a>
            <ul id="student-submenu">
                <li><a href="students.php">➕ Add Student</a></li>
                <li><a href="students.php">⚙️ Manage Students</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="toggleDeptMenu()">🏢 Departments ▼</a>
            <ul id="dept-submenu">
                <li><a href="adddepartment.php">➕ Add Department</a></li>
                <li><a href="managedepartments.php">📋 Manage Departments</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="toggleLeaveMenu()">📝 Leaves ▼</a>
            <ul id="leave-submenu">
                <li><a href="addleave.php">➕ Add Leave</a></li>
                <li><a href="manageleaves.php">📋 Manage Leaves</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="toggleLeaveManageMenu()">📤 Leave Management ▼</a>
            <ul id="leave-manage-submenu">
                <li><a href="allleaves.php">📋 All Leaves</a></li>
                <li><a href="pendingleaves.php">⏳ Pending</a></li>
                <li><a href="approvedleaves.php">✔️ Approved</a></li>
                <li><a href="notapprovedleaves.php">❌ Not Approved</a></li>
            </ul>
        </li>
        <li><a href="change-password.php">🔑 Change Password</a></li>
        <li><a href="logout.php">🚪 Sign Out</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h1>Change Password</h1>
    </div>

    <form method="POST">
        <label>Current Password</label>
        <input type="password" name="current_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Change Password</button>
        <div class="message"><?= $msg ?></div>
    </form>
</div>

<!-- JavaScript for menu toggle -->
<script>
function toggleStudentMenu() {
    let menu = document.getElementById("student-submenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
function toggleDeptMenu() {
    let menu = document.getElementById("dept-submenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
function toggleLeaveMenu() {
    let menu = document.getElementById("leave-submenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
function toggleLeaveManageMenu() {
    let menu = document.getElementById("leave-manage-submenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
</script>

</body>
</html>
