<?php
session_start();
$conn = new mysqli("localhost", "root", "", "slmsdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get leave types
$types = $conn->query("SELECT LeaveType FROM tblleavetype");

// Add leave
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $conn->real_escape_string($_POST['LeaveType']);
    $reason = $conn->real_escape_string($_POST['Description']);

    $studentId = 1; // <-- make dynamic if needed

    $insert = "INSERT INTO tblleaves (studentid, LeaveType, FromDate, ToDate, Reason, Status, AppliedDate)
               VALUES ($studentId, '$type', NOW(), NOW(), '$reason', 0, NOW())";

    if ($conn->query($insert)) {
        echo "<script>alert('Leave added successfully'); location.href='manageleaves.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SLMS | Add Leave</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4faff; /* light bluish like dashboard */
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #1e3c72, #2a5298); /* gradient like dashboard */
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: white;
            overflow-y: auto;
        }
        .sidebar h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px 20px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }
        .sidebar ul li a:hover {
            background-color: rgba(255,255,255,0.15);
            border-radius: 6px;
        }

        /* Main */
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        h1 {
            color: #2a5298;
            margin-bottom: 20px;
        }

        /* Form box styled like dashboard cards */
        .form-box {
            background: #fff;
            padding: 30px;
            max-width: 600px;
            border-radius: 10px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            color: #333;
        }
        select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #2a5298; /* same blue as dashboard */
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #1e3c72;
        }

        /* Submenus */
        ul li ul {
            display: none;
            padding-left: 20px;
        }
    </style>
    <script>
        function toggleMenu(id) {
            var menu = document.getElementById(id);
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }
    </script>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li>
            <a href="javascript:void(0);" onclick="toggleMenu('student-submenu')">🧑‍🎓 Students ▼</a>
            <ul id="student-submenu">
                <li><a href="students.php">➕ Add Student</a></li>
                <li><a href="students.php">⚙️ Manage Students</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="toggleMenu('dept-submenu')">🏢 Departments ▼</a>
            <ul id="dept-submenu">
                <li><a href="managedepartments.php">📋 Manage Departments</a></li>
                <li><a href="adddepartment.php">➕ Add Department</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="toggleMenu('leave-submenu')">📝 Leaves ▼</a>
            <ul id="leave-submenu">
                <li><a href="addleave.php">➕ Add Leave</a></li>
                <li><a href="manageleaves.php">📋 Manage Leaves</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="toggleMenu('leave-manage-submenu')">📤 Leave Management ▼</a>
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

<div class="main-content">
    <h1>Add Leave</h1>
    <div class="form-box">
        <form method="POST">
            <label>Leave Type</label>
            <select name="LeaveType" required>
                <option value="">-- Select Leave Type --</option>
                <?php while($row = $types->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['LeaveType']) ?>"><?= htmlspecialchars($row['LeaveType']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Reason</label>
            <textarea name="Description" rows="4" required placeholder="Enter leave reason here..."></textarea>

            <button type="submit">➕ Add Leave</button>
        </form>
    </div>
</div>

</body>
</html>
