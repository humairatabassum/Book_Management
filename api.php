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
$response = [
    'total_pages' => ceil(count($books) / $itemsPerPage),
    'books' => $books
];
header('Content-Type: application/json');

echo json_encode($response);
