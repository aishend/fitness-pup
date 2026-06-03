<?php

function getEquipmentByGroup(PDO $dbh, string $targetGroup): array {
    $stmt = $dbh->prepare("
        SELECT e.equipmentID, e.name, e.description, e.status, e.image, e.categoryID,
               c.name AS category, c.targetGroup
        FROM Equipment e
        JOIN EquipmentCategory c ON e.categoryID = c.categoryID
        WHERE c.targetGroup = ?
        ORDER BY c.categoryID, e.name
    ");
    $stmt->execute([$targetGroup]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEquipmentCategories(PDO $dbh, string $targetGroup): array {
    $stmt = $dbh->prepare("
        SELECT * FROM EquipmentCategory WHERE targetGroup = ? ORDER BY categoryID
    ");
    $stmt->execute([$targetGroup]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllEquipmentCategories(PDO $dbh): array {
    $stmt = $dbh->query("SELECT categoryID, name, targetGroup FROM EquipmentCategory ORDER BY targetGroup, name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEquipment(PDO $dbh, int $equipmentID): void {
    $stmt = $dbh->prepare("DELETE FROM Equipment WHERE equipmentID = ?");
    $stmt->execute([$equipmentID]);
}

function addEquipmentFull(PDO $dbh, string $name, ?string $description, int $categoryID, string $status, ?string $image): void {
    $stmt = $dbh->prepare("INSERT INTO Equipment (name, description, categoryID, status, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $categoryID, $status, $image]);
}

function updateEquipment(PDO $dbh, int $equipmentID, string $name, ?string $description, int $categoryID, string $status, ?string $image): void {
    if ($image !== null) {
        $stmt = $dbh->prepare("UPDATE Equipment SET name = ?, description = ?, categoryID = ?, status = ?, image = ? WHERE equipmentID = ?");
        $stmt->execute([$name, $description, $categoryID, $status, $image, $equipmentID]);
    } else {
        $stmt = $dbh->prepare("UPDATE Equipment SET name = ?, description = ?, categoryID = ?, status = ? WHERE equipmentID = ?");
        $stmt->execute([$name, $description, $categoryID, $status, $equipmentID]);
    }
}
?>