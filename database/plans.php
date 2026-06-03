<?php

function getAllPlans(PDO $dbh) {
    $stmt = $dbh->prepare('SELECT * FROM MembershipPlans ORDER BY planID ASC');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getPlanById(PDO $dbh, $planID) {
    $stmt = $dbh->prepare('SELECT * FROM MembershipPlans WHERE planID = ?');
    $stmt->execute(array($planID));
    return $stmt->fetch();
}

function getPlansForDisplay(PDO $dbh) {
    $plans = getAllPlans($dbh);
    $result = [];

    foreach ($plans as $plan) {
        $features_array = array_map('trim', explode(',', $plan['features']));
        $result[] = [
            'planID' => $plan['planID'],
            'name' => $plan['name'],
            'tagline' => $plan['tagline'],
            'price' => $plan['monthly_price'],
            'features' => $features_array,
            'is_featured' => $plan['is_featured']
        ];
    }

    return $result;
}

?>
