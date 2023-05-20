<?php
require_once "connection.php";

$itemsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;


$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$age = isset($_GET['age']) ? $_GET['age'] : '';
$bookTypes = isset($_GET['book_types']) ? $_GET['book_types'] : [];


$whereClause = '';
$params = [];

if (!empty($keyword)) {
    $whereClause .= "title LIKE :keyword OR publisher LIKE :keyword";
    $params['keyword'] = '%' . $keyword . '%';
}

if (!empty($age)) {
    $whereClause .= (!empty($whereClause) ? ' AND ' : '') . "age = :age";
    $params['age'] = $age;
}

if (!empty($bookTypes)) {
    $bookTypeParams = [];
    foreach ($bookTypes as $index => $bookType) {
        $bookTypeParams[] = ':book_type' . $index;
        $params['book_type' . $index] = $bookType;
    }

    $whereClause .= (!empty($whereClause) ? ' AND ' : '') . "book_type IN (" . implode(',', $bookTypeParams) . ")";
}

$offset = ($page - 1) * $itemsPerPage;

$query = "SELECT * FROM books";

if (!empty($whereClause)) {
    $query .= " WHERE " . $whereClause;
}

$query .= " ORDER BY id LIMIT :offset, :itemsPerPage";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

foreach ($params as $param => $value) {
    $stmt->bindValue($param, $value);
}

$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalCountQuery = "SELECT COUNT(*) FROM books";

if (!empty($whereClause)) {
    $totalCountQuery .= " WHERE " . $whereClause;
}

$totalCountStmt = $pdo->prepare($totalCountQuery);

foreach ($params as $param => $value) {
    $totalCountStmt->bindValue($param, $value);
}

$totalCountStmt->execute();
$totalCount = $totalCountStmt->fetchColumn();
$totalPages = ceil($totalCount / $itemsPerPage);



?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Management System</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>

<body>
    <h1>Book Management System</h1>

    <form action="index.php" method="GET">
        <label for="keyword">Keyword:</label>
        <input type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>">

        <label for="age">Age:</label>
        <select name="age" id="age">
            <option value="">Select Age</option>
            <option value="6-18">6-18</option>
            <option value="19-35">19-35</option>
            <option value="36-60">36-60</option>
        </select><br><br>
        <label for="book_types">Book Types:</label><br>
        <input type="checkbox" name="book_types[]" value="Scientific" <?php if (in_array('Scientific', $bookTypes)) echo 'checked'; ?>> Scientific<br>
        <input type="checkbox" name="book_types[]" value="Drama" <?php if (in_array('Drama', $bookTypes)) echo 'checked'; ?>> Drama<br>
        <input type="checkbox" name="book_types[]" value="Novel" <?php if (in_array('Novel', $bookTypes)) echo 'checked'; ?>> Novel<br>

        <input type="submit" value="Search">
    </form>

    <br>

    <table>
        <tr>
            <th>name of Book</th>
            <th>Name of Publisher</th>
            <th>Date of Publication</th>
            <th>Publisher Age</th>
            <th>Book Type</th>


        </tr>
        <?php foreach ($books as $book) : ?>
            <tr>
                <td><?php echo $book['title']; ?></td>
                <td><?php echo $book['publisher']; ?></td>
                <td><?php echo $book['application_date']; ?></td>
                <td><?php echo $book['age']; ?></td>
                <td><?php echo $book['book_type']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
</body>

</html>