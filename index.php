<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện Mini</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #005e9c;
            color: white;
            padding: 25px 15px;
        }

        .logo {
            font-size: 21px;
            font-weight: bold;
            padding: 0 15px 30px;
        }

        .menu-title {
            font-size: 12px;
            color: #bfdbfe;
            padding: 0 15px 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #4d9cc4;
        }

        .main {
            flex: 1;
            min-height: 100vh;
            padding: 35px 45px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .welcome {
            text-align: center;
        }

        .welcome h1 {
            font-size: 34px;
            color: #5071b2;
            margin-bottom: 12px;
        }

        .welcome p {
            color: #64748b;
            font-size: 17px;
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 190px;
            }

            .main {
                padding: 25px;
            }

            .welcome h1 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

    <div class="layout">

        <aside class="sidebar">

            <div class="logo">
                Thư viện Mini
            </div>

            <div class="menu-title">
                Quản lý
            </div>

            <nav class="menu">

                <a href="index.php" class="active">
                    🏠 Trang chủ
                </a>

                <a href="nguoiDung/User.php">👤 Người dùng</a>

                <a href="banSaoSach/bansao.php"> 📖 Bản sao sách </a>

            </nav>

        </aside>

        <main class="main">

            <div class="welcome">

                <h1>
                    Chào mừng đến với Thư viện Mini
                </h1>

                <p>
                    Hệ thống quản lý thư viện
                </p>

            </div>

        </main>

    </div>

</body>

</html>