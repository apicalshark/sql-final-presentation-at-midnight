<?php
require_once 'includes/db_connect.php';
$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: member_center.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "帳號和密碼必填";
    } else {
        $sql = "SELECT user_id, username, password, is_admin, name FROM dbo.users WHERE username = ?";
        $stmt = sqlsrv_query($conn, $sql, [$username]);

        if ($user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['is_admin'] = (bool) $user['is_admin'];
                header("Location: member_center.php");
                exit();
            } else {
                $error = "帳號或密碼錯誤。";
            }
        } else {
            $error = "帳號或密碼錯誤。";
        }
    }
}
require_once 'includes/header.php';
?>

<div class="flex items-center justify-center h-full pt-8 pb-60">
    <div class="w-full max-w-xs">
        <form action="login.php" method="POST" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">會員登入</h1>
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">使用者帳號</label>
                <input class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight"
                    id="username" name="username" type="text" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">密碼</label>
                <input class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight"
                    id="password" name="password" type="password" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="font-bold py-2 px-4 rounded bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900" type="submit">登入</button>
                <a class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition-colors" href="register.php">註冊新帳號</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>