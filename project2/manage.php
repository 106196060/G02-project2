<?php
session_start();

/*
    COS10026 Web Technology Project Part 2
    Manager page for Expressions of Interest.
*/

/* Protect the page */
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit;
}

require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

$page_title = "Nexora | Manage EOIs";
$body_class = "manage-page";
$current_page = "manage";

$message = "";
$error_message = "";

/* =========================================================
   UPDATE ONE EOI STATUS
   ========================================================= */

if (isset($_POST["update_status"])) {
    $eoi_number = filter_input(
        INPUT_POST,
        "eoi_number",
        FILTER_VALIDATE_INT
    );

    $new_status = trim($_POST["new_status"] ?? "");

    $allowed_statuses = [
        "New",
        "Current",
        "Final"
    ];

    if (
        !$eoi_number ||
        !in_array($new_status, $allowed_statuses, true)
    ) {
        $error_message = "Invalid EOI number or status.";
    } else {
        $update_sql = "
            UPDATE eoi
            SET status = ?
            WHERE EOInumber = ?
        ";

        $update_stmt = mysqli_prepare($conn, $update_sql);

        if ($update_stmt) {
            mysqli_stmt_bind_param(
                $update_stmt,
                "si",
                $new_status,
                $eoi_number
            );

            mysqli_stmt_execute($update_stmt);

            if (mysqli_stmt_affected_rows($update_stmt) > 0) {
                $message = "EOI status updated successfully.";
            } else {
                $message = "No status change was needed.";
            }

            mysqli_stmt_close($update_stmt);
        } else {
            $error_message = "Unable to update the EOI status.";
        }
    }
}

/* =========================================================
   DELETE ALL EOIs FOR A JOB REFERENCE
   ========================================================= */

if (isset($_POST["delete_by_job"])) {
    $delete_job_ref = strtoupper(
        trim($_POST["delete_job_ref"] ?? "")
    );

    if (!preg_match("/^[A-Z]{2}[0-9]{3}$/", $delete_job_ref)) {
        $error_message =
            "Enter a valid job reference such as NX001.";
    } else {
        $delete_sql = "
            DELETE FROM eoi
            WHERE job_ref = ?
        ";

        $delete_stmt = mysqli_prepare($conn, $delete_sql);

        if ($delete_stmt) {
            mysqli_stmt_bind_param(
                $delete_stmt,
                "s",
                $delete_job_ref
            );

            mysqli_stmt_execute($delete_stmt);

            $deleted_rows =
                mysqli_stmt_affected_rows($delete_stmt);

            if ($deleted_rows > 0) {
                $message =
                    $deleted_rows .
                    " EOI application(s) deleted for " .
                    $delete_job_ref .
                    ".";
            } else {
                $message =
                    "No EOI applications were found for " .
                    $delete_job_ref .
                    ".";
            }

            mysqli_stmt_close($delete_stmt);
        } else {
            $error_message =
                "Unable to delete the EOI applications.";
        }
    }
}

/* =========================================================
   SEARCH AND SORT
   ========================================================= */

$job_ref = trim($_GET["job_ref"] ?? "");
$first_name = trim($_GET["first_name"] ?? "");
$last_name = trim($_GET["last_name"] ?? "");
$sort = $_GET["sort"] ?? "EOInumber";

$allowed_sort_fields = [
    "EOInumber",
    "job_ref",
    "first_name",
    "last_name",
    "status"
];

if (!in_array($sort, $allowed_sort_fields, true)) {
    $sort = "EOInumber";
}

$sql = "SELECT * FROM eoi WHERE 1 = 1";

$params = [];
$types = "";

if ($job_ref !== "") {
    $sql .= " AND job_ref = ?";
    $params[] = strtoupper($job_ref);
    $types .= "s";
}

if ($first_name !== "") {
    $sql .= " AND first_name LIKE ?";
    $params[] = "%" . $first_name . "%";
    $types .= "s";
}

if ($last_name !== "") {
    $sql .= " AND last_name LIKE ?";
    $params[] = "%" . $last_name . "%";
    $types .= "s";
}

$sql .= " ORDER BY $sort ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Unable to prepare the EOI query.");
}

if (!empty($params)) {
    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Unable to load EOI records.");
}

include("header.inc");
include("nav.inc");
?>

<main class="manage-container">

    <section>
        <p class="eyebrow">HR PORTAL</p>

        <h1>Manage Expressions of Interest</h1>

        <p>
            Logged in as
            <strong>
                <?php
                echo htmlspecialchars(
                    $_SESSION["admin_username"] ?? "Admin"
                );
                ?>
            </strong>
        </p>

        <p>
            <a href="logout.php">Log Out</a>
        </p>
    </section>

    <?php if ($message !== ""): ?>

        <p class="success-message">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <?php if ($error_message !== ""): ?>

        <p class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </p>

    <?php endif; ?>

    <section>
        <h2>Search and Sort EOIs</h2>

        <form action="manage.php" method="get">

            <div class="form-group">
                <label for="job_ref">
                    Job Reference
                </label>

                <input
                    type="text"
                    id="job_ref"
                    name="job_ref"
                    value="<?php
                    echo htmlspecialchars($job_ref);
                    ?>"
                    placeholder="Example: NX001"
                >
            </div>

            <div class="form-group">
                <label for="first_name">
                    First Name
                </label>

                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="<?php
                    echo htmlspecialchars($first_name);
                    ?>"
                >
            </div>

            <div class="form-group">
                <label for="last_name">
                    Last Name
                </label>

                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="<?php
                    echo htmlspecialchars($last_name);
                    ?>"
                >
            </div>

            <div class="form-group">
                <label for="sort">
                    Sort By
                </label>

                <select id="sort" name="sort">

                    <option
                        value="EOInumber"
                        <?php
                        if ($sort === "EOInumber") {
                            echo "selected";
                        }
                        ?>
                    >
                        EOI Number
                    </option>

                    <option
                        value="job_ref"
                        <?php
                        if ($sort === "job_ref") {
                            echo "selected";
                        }
                        ?>
                    >
                        Job Reference
                    </option>

                    <option
                        value="first_name"
                        <?php
                        if ($sort === "first_name") {
                            echo "selected";
                        }
                        ?>
                    >
                        First Name
                    </option>

                    <option
                        value="last_name"
                        <?php
                        if ($sort === "last_name") {
                            echo "selected";
                        }
                        ?>
                    >
                        Last Name
                    </option>

                    <option
                        value="status"
                        <?php
                        if ($sort === "status") {
                            echo "selected";
                        }
                        ?>
                    >
                        Status
                    </option>

                </select>
            </div>

            <button type="submit">
                Search
            </button>

            <a href="manage.php">
                Clear Filters
            </a>

        </form>
    </section>

    <section>
        <h2>Delete EOIs by Job Reference</h2>

        <p>
            This deletes every application submitted for
            the selected job.
        </p>

        <form action="manage.php" method="post">

            <label for="delete_job_ref">
                Job Reference
            </label>

            <input
                type="text"
                id="delete_job_ref"
                name="delete_job_ref"
                placeholder="Example: NX002"
            >

            <button
                type="submit"
                name="delete_by_job"
            >
                Delete EOIs
            </button>

        </form>
    </section>

    <section>
        <h2>EOI Results</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>

            <div class="table-wrapper">

                <table>
                    <thead>
                        <tr>
                            <th>EOI Number</th>
                            <th>Job Reference</th>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>
                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["EOInumber"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["job_ref"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["first_name"] .
                                    " " .
                                    $row["last_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["email"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["phone"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["status"]
                                );
                                ?>
                            </td>

                            <td>
                                <form
                                    action="manage.php"
                                    method="post"
                                >
                                    <input
                                        type="hidden"
                                        name="eoi_number"
                                        value="<?php
                                        echo htmlspecialchars(
                                            $row["EOInumber"]
                                        );
                                        ?>"
                                    >

                                    <label
                                        for="status-<?php
                                        echo htmlspecialchars(
                                            $row["EOInumber"]
                                        );
                                        ?>"
                                    >
                                        Status
                                    </label>

                                    <select
                                        id="status-<?php
                                        echo htmlspecialchars(
                                            $row["EOInumber"]
                                        );
                                        ?>"
                                        name="new_status"
                                    >
                                        <option
                                            value="New"
                                            <?php
                                            if (
                                                $row["status"] ===
                                                "New"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            New
                                        </option>

                                        <option
                                            value="Current"
                                            <?php
                                            if (
                                                $row["status"] ===
                                                "Current"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Current
                                        </option>

                                        <option
                                            value="Final"
                                            <?php
                                            if (
                                                $row["status"] ===
                                                "Final"
                                            ) {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Final
                                        </option>
                                    </select>

                                    <button
                                        type="submit"
                                        name="update_status"
                                    >
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                    </tbody>
                </table>

            </div>

        <?php else: ?>

            <p>
                No matching EOI applications were found.
            </p>

        <?php endif; ?>

    </section>

</main>

<?php
mysqli_free_result($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

include("footer.inc");
?>