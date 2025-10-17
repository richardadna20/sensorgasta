<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Monitoring Gas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f9ff;
            display: flex;
        }

        .sidebar {
            width: 240px;
            background: linear-gradient(to bottom, #3b82f6, #2563eb);
            color: white;
            height: 100vh;
            transition: width 0.3s ease;
            overflow: hidden;
            position: fixed;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar h2 {
            font-size: 22px;
            text-align: center;
            padding: 20px 10px;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed h2 {
            opacity: 0;
            height: 0;
            padding: 0;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            margin: 10px 10px;
            cursor: pointer;
            align-self: flex-start;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .sidebar ul li {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            cursor: pointer;
        }

        .sidebar ul li:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li i {
            margin-right: 10px;
            font-size: 20px;
            width: 20px;
            text-align: center;
        }

        .sidebar ul li span {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed ul li span {
            display: none;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed~.main {
            margin-left: 70px;
        }

        .card,
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .charts {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .chart-container {
            flex: 1;
            min-width: 300px;
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <h2 id="sidebarTitle">GASMonitoring</h2>
        <ul>
            <li><i>🏠</i><a href="/dashboard" style="color:white;text-decoration:none;"><span>Dashboard</span></a></li>
            <li><i>📟</i><a href="/datasensor" style="color:white;text-decoration:none;"><span>Data Sensor</span></li>
            <li><i>📈</i><a href="/grafik" style="color:white;text-decoration:none;"><span>Grafik</span></li>
            <li><i>👤</i><a href="/profil" style="color:white;text-decoration:none;"><span>Profil</span></a></li>
            <li><i>🚪</i><a href="/logout" style="color:white;text-decoration:none;"><span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main">
        @yield('content')
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>

    @yield('extra-js')
</body>

</html>
