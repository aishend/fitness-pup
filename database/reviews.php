<?php

function getCompletedClassesWithReviews(PDO $dbh, $userID): array {
    $stmt = $dbh->prepare("
        SELECT CE.enrollmentID, C.classID, C.name AS class_name, C.start_time, C.type,
               U.name AS trainerName,
               R.reviewID, R.rating, R.comment, R.review_date
        FROM Classes_Enrollments CE
        JOIN Classes C ON CE.classID = C.classID
        JOIN Trainers T ON C.trainerID = T.trainerID
        JOIN Users U ON T.userID = U.userID
        LEFT JOIN Reviews R ON CE.enrollmentID = R.enrollmentID
        WHERE CE.userID = ? AND CE.status = 'enrolled' AND C.class_status = 'completed'
        ORDER BY C.start_time DESC
    ");
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function submitReview(PDO $dbh, $enrollmentID, $rating, $comment): bool {
    $stmt = $dbh->prepare("
        INSERT INTO Reviews (enrollmentID, rating, comment)
        VALUES (?, ?, ?)
    ");
    return $stmt->execute([$enrollmentID, $rating, $comment]);
}

function canUserReviewEnrollment(PDO $dbh, $userID, $enrollmentID): bool {
    $stmt = $dbh->prepare("
        SELECT CE.enrollmentID
        FROM Classes_Enrollments CE
        JOIN Classes C ON CE.classID = C.classID
        LEFT JOIN Reviews R ON CE.enrollmentID = R.enrollmentID
        WHERE CE.enrollmentID = ?
          AND CE.userID = ?
          AND CE.status = 'enrolled'
          AND C.class_status = 'completed'
          AND R.reviewID IS NULL
    ");
    $stmt->execute([$enrollmentID, $userID]);
    return (bool)$stmt->fetch();
}

function getReviewsForTrainer(PDO $dbh, $trainerID): array {
    $stmt = $dbh->prepare("
        SELECT R.rating, R.comment, R.review_date,
               C.name AS class_name, C.start_time
        FROM Reviews R
        JOIN Classes_Enrollments CE ON R.enrollmentID = CE.enrollmentID
        JOIN Classes C ON CE.classID = C.classID
        WHERE C.trainerID = ?
        ORDER BY R.review_date DESC
    ");
    $stmt->execute([$trainerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteReview(PDO $dbh, $reviewID, $userID): bool {
    $stmt = $dbh->prepare("
        DELETE FROM Reviews
        WHERE reviewID = ?
          AND enrollmentID IN (
              SELECT enrollmentID FROM Classes_Enrollments WHERE userID = ?
          )
    ");
    return $stmt->execute([$reviewID, $userID]);
}
?>
