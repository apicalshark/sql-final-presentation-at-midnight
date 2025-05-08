<html>

<head>
<link href="style/db_query.css" rel="stylesheet">
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>ProductID</th>
                <th>QuantityPerUnit</th>
                <th>ProductName</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "db_connect.php";
            $sql = "SELECT top 5 * from Products";
            $qury = sqlsrv_query($conn, $sql) or die("sql error" . sqlsrv_errors());
            while ($row = sqlsrv_fetch_array($qury)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ProductID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['QuantityPerUnit']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                echo "</tr>";
            }
            sqlsrv_close($conn);
            ?>
        </tbody>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th>EmployeeID</th>
                <th>EmployeeName</th>
                <th>Title</th>
                <th>Address</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "db_connect.php";
            $sql = "select top 5 EmployeeID, FirstName + ' ' + LastName as EmployeeName,
                    Title, Address, City from dbo.Employees";
            $qury = sqlsrv_query($conn, $sql) or die("sql error". sqlsrv_errors());
            while ($row = sqlsrv_fetch_array($qury)) {
                echo "<tr>";
                echo "<td>". htmlspecialchars($row["EmployeeID"]) . "</td>";
                echo "<td>". htmlspecialchars($row["EmployeeName"]) . "</td>";
                echo "<td>". htmlspecialchars($row["Title"]) . "</td>";
                echo "<td>". htmlspecialchars($row["Address"]) . "</td>";
                echo "<td>". htmlspecialchars($row["City"]) . "</td>";
                echo "</tr>";
            }
            sqlsrv_close($conn);
            ?>

</body>

</html>