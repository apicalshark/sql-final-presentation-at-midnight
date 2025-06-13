<?php
require_once 'includes/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$book_id = (int) $_GET['id'];
$sql = "SELECT * FROM dbo.books WHERE book_id = ?";
$params = array($book_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$book = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$book) {
    require_once 'includes/header.php';
    echo "<div class='bg-white p-8 rounded-lg shadow-md text-center'>";
    echo "<h1 class='text-2xl font-bold text-gray-900'>找不到書籍</h1>";
    echo "<p class='text-gray-600 mt-4'>您要找的書籍不存在</p>";
    echo "<a href='index.php' class='mt-6 inline-block bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900 font-bold py-2 px-4 rounded'>返回首頁</a>";
    echo "</div>";
    require_once 'includes/footer.php';
    exit();
}

require_once 'includes/header.php';
?>

<div class="flex flex-col bg-gray-100 p-20 rounded-lg shadow-lg justify-center items-center flex-grow h-full">
    <div class="gap-8">
        <div class="flex flex-col justify-center">
            <h1 class="text-4xl font-bold text-gray-900"><?php echo htmlspecialchars($book['title']); ?></h1>
            <p class="text-xl text-gray-700 mt-2">作者：<?php echo htmlspecialchars($book['author']); ?></p>

            <?php if (!empty($book['isbn'])): ?>
                <p class="text-md text-gray-500 mt-4">ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
            <?php endif; ?>

            <p class="text-4xl font-bold text-red-600 my-6">NT$ <?php echo number_format($book['price'], 0); ?></p>

            <div class="prose max-w-none text-gray-600 mt-4">
                <h3 class="font-bold border-b border-gray-300 pb-2 mb-2">書籍描述</h3>
                <p><?php echo !empty($book['description']) ? nl2br(htmlspecialchars($book['description'])) : '暫無描述。'; ?>
                </p>
            </div>

            <form action="cart.php" method="POST" class="mt-8">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">

                <div class="flex items-center space-x-4">
                    <label for="quantity" class="font-bold">數量:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1"
                        max="<?php echo (int) $book['stock']; ?>"
                        class="w-20 p-2 border border-gray-300 rounded-md text-center">

                    <?php if ((int) $book['stock'] > 0): ?>
                        <button type="submit"
                            class="bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900 font-bold py-3 px-6 rounded-lg">
                            加入購物車
                        </button>
                    <?php else: ?>
                        <button type="button"
                            class="bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed" disabled>
                            已售完
                        </button>
                    <?php endif; ?>
                </div>
                <p class="text-sm text-gray-500 mt-2">目前庫存：<?php echo (int) $book['stock']; ?></p>
            </form>
        </div>
    </div>
</div>

<?php
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
require_once 'includes/footer.php';
?>