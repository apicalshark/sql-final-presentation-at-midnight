<?php
require_once 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT dbo.orders.order_id , dbo.orders.order_date, dbo.orders.total_amount, dbo.orders.status, dbo.books.title, 
        dbo.order_items.quantity * dbo.order_items.price_at_order AS total_price
        FROM dbo.orders 
        JOIN dbo.order_items ON dbo.orders.order_id = dbo.order_items.order_id 
        JOIN dbo.books ON dbo.order_items.book_id = dbo.books.book_id 
        WHERE dbo.orders.user_id = ? ORDER BY dbo.orders.order_date DESC";
$stmt = sqlsrv_query($conn, $sql, [$user_id]);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$orders = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $orders[] = $row;
}

require_once 'includes/header.php';
?>

<div class="bg-white py-8 px-4 sm:px-40 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">會員中心</h1>
    <p class="text-gray-600 mb-6">歡迎回來, <?php echo htmlspecialchars($_SESSION['name']); ?>！</p>
    
    <?php if (isset($_GET['status']) && $_GET['status'] == 'order_success'): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">訂單已成功送出！</strong>
            <span class="block sm:inline">您的訂單編號是 #<?php echo htmlspecialchars($_GET['order_id']); ?>。</span>
        </div>
    <?php endif; ?>

    <h2 class="text-2xl font-semibold text-gray-800 mt-8 border-t border-gray-300 pt-6">您的歷史訂單</h2>

    <?php if (empty($orders)): ?>
        <p class="text-gray-600 mt-4">您目前沒有任何訂單記錄。</p>
    <?php else: ?>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">訂單編號</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">書籍標題</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">下單日期</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">總金額</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">訂單狀態</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $order['order_id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($order['title']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $order['order_date']->format('Y-m-d H:i:s'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">NT$ <?php echo number_format($order['total_price'], 0); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($order['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
require_once 'includes/footer.php';
?>