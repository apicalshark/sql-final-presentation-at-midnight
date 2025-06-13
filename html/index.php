<?php
require_once 'includes/db_connect.php';
require 'includes/header.php';

$sql = "SELECT book_id, title, author, price, stock FROM dbo.books ORDER BY created_at DESC";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<div class="bg-white px-4 sm:px-40 py-8 rounded-lg shadow">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">最新上架</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 py-2">
        <?php while ($book = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)):?>
            <div class="bg-white shadow-md overflow-hidden rounded-lg transform transition-transform duration-300 border border-gray-200">
                <div class="p-7">
                    <h2 class="text-xl font-semibold text-gray-800 h-12 truncate">
                        <?php echo htmlspecialchars($book['title']); ?>
                    </h2>
                    <p class="text-gray-600">作者：<?php echo htmlspecialchars($book['author']); ?></p>
                    <p class="text-lg font-bold text-red-600 mt-4">NT$ <?php echo number_format($book['price'], 2); ?></p>
                    <p class="text-sm text-gray-500 mt-1">庫存：<?php echo (int) $book['stock']; ?></p>
                    <div class="mt-4">
                        <a href="book_details.php?id=<?php echo $book['book_id']; ?>"
                            class="w-full text-center block font-bold py-2 px-4 rounded-lg bg-gray-900 hover:bg-gray-300 transition-colors text-white hover:text-gray-900">
                            查看詳情
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    if (sqlsrv_has_rows($stmt) === false) {
        echo "<p class='text-gray-700'>目前書店沒有任何書籍。</p>";
    }
    ?>
</div>
<?php
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

require_once 'includes/footer.php';
?>