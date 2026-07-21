<?php
session_start();

require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error_message = "Please enter both username and password.";
    } else {
        $sql = "SELECT user_id, username, password, role
                FROM users
                WHERE username = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user_record = mysqli_fetch_assoc($result);

        if (
            $user_record &&
            password_verify($password, $user_record["password"]) &&
            $user_record["role"] === "admin"
        ) {
            session_regenerate_id(true);

            $_SESSION["admin_logged_in"] = true;
            $_SESSION["admin_username"] = $user_record["username"];

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: manage.php");
            exit;
        }

        $error_message = "Invalid username or password.";

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);

$page_title = "HR Login";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($page_title); ?> | Nexora</title>

    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

<main class="login-page">

    <section class="login-card">

        <p class="eyebrow">HR PORTAL</p>

        <h1>Manager Login</h1>

        <p>Sign in to view and manage Expressions of Interest.</p>

        <?php if ($error_message !== ""): ?>
            <p class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </p>
        <?php endif; ?>

        <form action="login.php" method="post">

            <div class="form-group">
                <label for="username">Username</label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlspecialchars($_POST["username"] ?? ""); ?>"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                >
            </div>

            <button type="submit">Log In</button>

        </form>

    </section>

</main>

</body>
</html>