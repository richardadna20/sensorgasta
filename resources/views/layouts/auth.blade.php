<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Monitoring Gas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Latar belakang gradient biru */
            background: linear-gradient(to right, #3b82f6, #2563eb); 
        }

        .auth-container {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #1e3a8a;
        }

        .form-group {
            text-align: left;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            margin-top: 6px;
            outline: none;
            transition: border 0.2s;
        }

        .form-group input:focus {
            border: 1px solid #2563eb;
            box-shadow: 0 0 4px rgba(37, 99, 235, 0.4);
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(to right, #2563eb, #1d4ed8);
        }

        .error-message {
            color: #dc2626;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: left;
        }

        .auth-link {
            margin-top: 15px;
            font-size: 14px;
            color: #374151;
        }

        .auth-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        /* --- Custom Styles for Right Alignment --- */
        .password-header {
            display: flex;
            justify-content: space-between; /* Mendorong label ke kiri, link ke kanan */
            align-items: center;
            margin-bottom: 6px;
        }
        
        .password-header label {
            margin-bottom: 0; /* Hapus margin atas/bawah yang tidak perlu */
        }
        
        .password-header .forgot-link {
            font-size: 12px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        
    </style>
</head>

<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>

</html>
