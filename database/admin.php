<?php

function deleteUser(PDO $dbh, $userID) {
    $stmt = $dbh->prepare("DELETE FROM Users WHERE userID = ?");
    $stmt->execute(array($userID));
}

function getUsersFiltered(PDO $dbh, $filter = 'all', $search = '') {
    if ($filter === 'all') {
        $query = "SELECT * FROM Users WHERE role IN ('member', 'trainer', 'pet-trainer', 'admin')";
        $params = [];
    } else {
        $query = "SELECT * FROM Users WHERE role = ?";
        $params = [$filter];
    }

    if ($search) {
        $query .= " AND (name LIKE ? OR username LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $query .= " ORDER BY role, name ASC";

    $stmt = $dbh->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
