<?php
require_once 'includes/db_connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout');
    exit();
}

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['cart'];
$total_amount = 0;

foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (sqlsrv_begin_transaction($conn) === false) {
        die("Failed to start transaction. Error: " . print_r(sqlsrv_errors(), true));
    }
    $order_sql = "INSERT INTO dbo.orders (user_id, total_amount, status) OUTPUT INSERTED.order_id VALUES (?, ?, ?)";
    $order_params = [$user_id, $total_amount, 'Pending'];
    $order_stmt = sqlsrv_query($conn, $order_sql, $order_params);
    
    if ($order_stmt && ($row = sqlsrv_fetch_array($order_stmt, SQLSRV_FETCH_ASSOC))) {
        $order_id = $row['order_id'];
        $all_items_inserted = true;

        foreach ($cart_items as $book_id => $item) {
            $item_sql = "INSERT INTO dbo.order_items (order_id, book_id, quantity, price_at_order) VALUES (?, ?, ?, ?)";
            $item_params = [$order_id, $book_id, $item['quantity'], $item['price']];
            $item_stmt = sqlsrv_query($conn, $item_sql, $item_params);
            if (!$item_stmt) {
                $all_items_inserted = false;
                break;
            }
            $stock_sql = "UPDATE dbo.books SET stock = stock - ? WHERE book_id = ?";
            $stock_params = [$item['quantity'], $book_id];
            $stock_stmt = sqlsrv_query($conn, $stock_sql, $stock_params);

            if (!$stock_stmt) {
                $all_items_inserted = false;
                break;
            }
        }

        if ($all_items_inserted) {
            sqlsrv_commit($conn);
            unset($_SESSION['cart']);
            header("Location: member_center.php?status=order_success&order_id=" . $order_id);
            exit();
        }
    }
    
    sqlsrv_rollback($conn);
    header("Location: cart.php?error=checkout_failed");
    exit();
}
require_once 'includes/header.php';
?>

<div class="bg-white sm:px-40 px-4 py-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b border-gray-300 pb-4">訂單確認</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <div>
            <h2 class="text-xl font-semibold mb-4">訂單商品</h2>
            <div class="border border-gray-300 rounded-lg p-4 divide-y divide-gray-200">
                <?php foreach($cart_items as $item): ?>
                    <div class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium"><?php echo htmlspecialchars($item['title']); ?></p>
                            <p class="text-sm text-gray-500">NT$ <?php echo number_format($item['price'], 0); ?> x <?php echo $item['quantity']; ?></p>
                        </div>
                        <p class="font-semibold">NT$ <?php echo number_format($item['price'] * $item['quantity'], 0); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-500 text-right">
                <p class="text-2xl font-bold">總金額: <span class="text-red-600">NT$ <?php echo number_format($total_amount, 0); ?></span></p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-4">寄送資訊</h2>
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                <p class="mb-2"><span class="font-semibold">姓名:</span> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                <p><span class="font-semibold">帳號:</span> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">付款方式</h2>
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                     <p>貨到付款</p>
                </div>
            </div>
            <form action="checkout.php" method="POST" class="mt-8">
                <button type="submit" name="place_order" class="w-full bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900 font-bold py-3 px-6 rounded-lg text-xl">
                    確認下單
                </button>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
sqlsrv_close($conn);
?>