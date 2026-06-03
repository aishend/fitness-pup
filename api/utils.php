<?php

function handlePhotoUpload(string $fileKey, string $destDir, string $prefix, int $userID): string|false
{
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        throw new InvalidArgumentException('Invalid image format. Use JPG, PNG or WEBP.');
    }

    $filename = $prefix . $userID . '_' . time() . '.' . $ext;

    return move_uploaded_file($_FILES[$fileKey]['tmp_name'], $destDir . $filename) ? $filename : false;
}
