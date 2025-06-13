<?php
require_once 'includes/db_connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['remove_book_id'])) {
        $book_id_to_remove = (int)$_POST['remove_book_id'];
        if (isset($_SESSION['cart'][$book_id_to_remove])) {
            unset($_SESSION['cart'][$book_id_to_remove]);
        }
    } 
    else if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (isset($_POST['book_id'], $_POST['quantity']) && is_numeric($_POST['book_id']) && is_numeric($_POST['quantity'])) {
                    $book_id = (int)$_POST['book_id'];
                    $quantity = (int)$_POST['quantity'];
                    $sql = "SELECT title, price, stock FROM dbo.books WHERE book_id = ?";
                    $stmt = sqlsrv_query($conn, $sql, [$book_id]);
                    $book = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                    if ($book && $quantity > 0 && $book['stock'] >= $_SESSION['cart'][$book_id]['quantity'] + $quantity) {
                        if (isset($_SESSION['cart'][$book_id])) {
                            $_SESSION['cart'][$book_id]['quantity'] += $quantity;
                        } else {
                            $_SESSION['cart'][$book_id] = [
                                'title' => $book['title'], 'price' => $book['price'], 'quantity' => $quantity
                            ];
                        }
                    }
                }
                break;
        }
    }
    
    header('Location: cart.php');
    exit();
}

require_once 'includes/header.php';
?>

<div class="bg-white p-8 sm:px-40 px-4 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b border-gray-300 pb-4">您的購物車</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p class="text-gray-600">您的購物車是空的。</p>
        <a href="index.php" class="mt-6 inline-block bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900 font-bold py-2 px-4 rounded">繼續購物</a>
    <?php else: ?>
        <form action="cart.php" method="POST">
            <input type="hidden" name="action" value="update">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider overflow-hidden">商品</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">單價</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">數量</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">小計</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $total_amount = 0;
                        foreach ($_SESSION['cart'] as $id => $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total_amount += $subtotal;
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['title']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">NT$ <?php echo number_format($item['price'], 0); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600"><?php echo $item['quantity']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">NT$ <?php echo number_format($subtotal, 0); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="submit" name="remove_book_id" value="<?php echo $id; ?>" class="text-red-600 hover:text-red-900">移除</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="text-right">
                    <p class="text-xl font-bold text-gray-900">總計: NT$ <?php echo number_format($total_amount, 0, '.', ','); ?></p>
                </div>
            </div>
        </form>

        <div class="mt-8 text-left">
            <a href="checkout.php" class="font-bold py-3 px-8 rounded-lg bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900">前往結帳</a>
        </div>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
if ($conn) { sqlsrv_close($conn); }
?>