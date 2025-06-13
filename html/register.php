<?php
require_once 'includes/db_connect.php';
$errors = [];

if (isset($_SESSION['user_id'])) {
    header('Location: member_center.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);

    if (empty($username) || empty($password) || empty($email) || empty($name)) {
        $errors[] = "所有欄位都是必填的。";
    }
    if ($password !== $password_confirm) {
        $errors[] = "兩次輸入的密碼不相符。";
    }
    if (strlen($password) < 6) {
        $errors[] = "密碼長度至少需要6個字元。";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "電子郵件格式不正確。";
    }

    if (empty($errors)) {
        $sql = "SELECT user_id FROM dbo.users WHERE username = ? OR email = ?";
        $params = [$username, $email];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (sqlsrv_has_rows($stmt)) {
            $errors[] = "該名稱或電子郵件已被註冊。";
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql_insert = "INSERT INTO dbo.users (username, password, email, name) VALUES (?, ?, ?, ?)";
        $params_insert = [$username, $hashed_password, $email, $name];
        $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

        if ($stmt_insert) {
            header('Location: login.php?status=registered');
            exit();
        } else {
            $errors[] = "註冊失敗，請稍後再試。";
            die(print_r(sqlsrv_errors(), true));
        }
    }
}

require_once 'includes/header.php';
?>

<div class="flex items-center justify-center h-full pt-8 pb-12">
    <div class="w-full max-w-md">
        <form action="register.php" method="POST" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">建立帳戶</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">使用者帳號</label>
                <input class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight" id="username" name="username" type="text" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">姓名</label>
                <input class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight" id="name" name="name" type="text" required>
            </div>
             <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">電子郵件</label>
                <input class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight" id="email" name="email" type="email" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">密碼</label>
                <input class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight" id="password" name="password" type="password" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirm">確認密碼</label>
                <input class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight" id="password_confirm" name="password_confirm" type="password" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="font-bold py-2 px-4 rounded bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900" type="submit">註冊</button>
                <a class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition-colors" href="login.php">
                    已經有帳號了? 前往登入
                </a>
            </div>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>