<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>網路書店 Demo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 font-sans flex flex-col min-h-screen">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto py-2 px-6 lg:px-8 w-full flex-grow">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">網路書店 Demo</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php"
                        class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">首頁</a>
                    <a href="cart.php"
                        class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">購物車</a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="member_center.php"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">會員中心</a>
                        <a href="logout.php"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">登出</a>
                    <?php else: ?>
                        <a href="login.php"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">登入</a>
                        <a href="register.php"
                            class="bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">註冊</a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>