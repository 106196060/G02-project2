<?php
if (!isset($page_title)) {
    $page_title = "Nexora";
}

if (!isset($body_class)) {
    $body_class = "";
}
?>
/*
    COS10026 Web Technology
    Project Part 2

    Shared header include
    Used on all pages
*/
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Nexora is a non-profit organisation building technology capacity with volunteers.">
    <meta name="keywords" content="Nexora, technology, jobs, community, digital inclusion">
    <meta name="author" content="Group 2 - Nexora Project Team">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($page_title); ?></title>

    <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="<?php echo htmlspecialchars($body_class); ?>">
