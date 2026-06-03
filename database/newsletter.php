<?php

function addNewsletterSubscriber(PDO $dbh, string $email): bool {
    $stmt = $dbh->prepare('INSERT OR IGNORE INTO NewsletterSubscribers (email) VALUES (?)');
    return $stmt->execute([$email]);
}

function isNewsletterSubscriber(PDO $dbh, string $email): bool {
    $stmt = $dbh->prepare('SELECT 1 FROM NewsletterSubscribers WHERE email = ?');
    $stmt->execute([$email]);
    return (bool) $stmt->fetch();
}

function getAllNewsletterSubscribers(PDO $dbh): array {
    $stmt = $dbh->prepare('SELECT email, subscribed_at FROM NewsletterSubscribers ORDER BY subscribed_at DESC');
    $stmt->execute();
    return $stmt->fetchAll();
}
