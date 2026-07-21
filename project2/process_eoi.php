<?php
/*
 * Nexora EOI form processor
 * This page only accepts form submissions from apply.php.
 */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit;
}

require_once("settings.php");

/* ---------------------------------------------------------
   FUNCTIONS
   --------------------------------------------------------- */

function clean_input(string $value): string
{
    return trim($value);
}

function calculate_age(DateTime $birth_date): int
{
    $today = new DateTime();
    return $today->diff($birth_date)->y;
}

function postcode_matches_state(
    string $postcode,
    string $state
): bool {
    $first_digit = $postcode[0];

    $valid_first_digits = [
        "VIC" => ["3", "8"],
        "NSW" => ["1", "2"],
        "QLD" => ["4", "9"],
        "NT"  => ["0"],
        "WA"  => ["6"],
        "SA"  => ["5"],
        "TAS" => ["7"],
        "ACT" => ["2"]
    ];

    return isset($valid_first_digits[$state])
        && in_array(
            $first_digit,
            $valid_first_digits[$state],
            true
        );
}

/* ---------------------------------------------------------
   RECEIVE AND CLEAN FORM DATA
   --------------------------------------------------------- */

$job_ref = strtoupper(
    clean_input($_POST["job_ref"] ?? "")
);

$first_name = clean_input(
    $_POST["first_name"] ?? ""
);

$last_name = clean_input(
    $_POST["last_name"] ?? ""
);

$date_of_birth_input = clean_input(
    $_POST["date_of_birth"] ?? ""
);

$gender = clean_input(
    $_POST["gender"] ?? ""
);

$street_address = clean_input(
    $_POST["street_address"] ?? ""
);

$suburb_town = clean_input(
    $_POST["suburb_town"] ?? ""
);

$state = strtoupper(
    clean_input($_POST["state"] ?? "")
);

$postcode = clean_input(
    $_POST["postcode"] ?? ""
);

$email = clean_input(
    $_POST["email"] ?? ""
);

$phone = clean_input(
    $_POST["phone"] ?? ""
);

$submitted_skills = $_POST["skills"] ?? [];

$other_skills = clean_input(
    $_POST["other_skills"] ?? ""
);

$errors = [];

/* ---------------------------------------------------------
   VALIDATE JOB REFERENCE
   --------------------------------------------------------- */

if ($job_ref === "") {
    $errors[] = "Job reference number is required.";
} elseif (!preg_match("/^[A-Z0-9]{5}$/", $job_ref)) {
    $errors[] =
        "Job reference must contain exactly five letters or numbers.";
}

/* ---------------------------------------------------------
   VALIDATE NAME
   --------------------------------------------------------- */

if ($first_name === "") {
    $errors[] = "First name is required.";
} elseif (
    !preg_match("/^[A-Za-z ]{1,20}$/", $first_name)
) {
    $errors[] =
        "First name must contain only letters and spaces and be no more than 20 characters.";
}

if ($last_name === "") {
    $errors[] = "Last name is required.";
} elseif (
    !preg_match("/^[A-Za-z ]{1,20}$/", $last_name)
) {
    $errors[] =
        "Last name must contain only letters and spaces and be no more than 20 characters.";
}

/* ---------------------------------------------------------
   VALIDATE DATE OF BIRTH AND AGE
   --------------------------------------------------------- */

$date_of_birth_database = "";

if ($date_of_birth_input === "") {
    $errors[] = "Date of birth is required.";
} else {
    $birth_date = DateTime::createFromFormat(
        "d/m/Y",
        $date_of_birth_input
    );

    $date_errors = DateTime::getLastErrors();

    $valid_date =
        $birth_date !== false
        && (
            $date_errors === false
            || (
                $date_errors["warning_count"] === 0
                && $date_errors["error_count"] === 0
            )
        )
        && $birth_date->format("d/m/Y")
            === $date_of_birth_input;

    if (!$valid_date) {
        $errors[] =
            "Date of birth must be a valid date in dd/mm/yyyy format.";
    } else {
        $age = calculate_age($birth_date);

        if ($age < 15 || $age > 80) {
            $errors[] =
                "Applicants must be between 15 and 80 years old.";
        }

        $date_of_birth_database =
            $birth_date->format("Y-m-d");
    }
}

/* ---------------------------------------------------------
   VALIDATE GENDER
   --------------------------------------------------------- */

$allowed_genders = [
    "Male",
    "Female",
    "Other"
];

if (!in_array($gender, $allowed_genders, true)) {
    $errors[] = "Please select a valid gender.";
}

/* ---------------------------------------------------------
   VALIDATE ADDRESS
   --------------------------------------------------------- */

if ($street_address === "") {
    $errors[] = "Street address is required.";
} elseif (strlen($street_address) > 40) {
    $errors[] =
        "Street address cannot exceed 40 characters.";
}

if ($suburb_town === "") {
    $errors[] = "Suburb or town is required.";
} elseif (strlen($suburb_town) > 40) {
    $errors[] =
        "Suburb or town cannot exceed 40 characters.";
}

/* ---------------------------------------------------------
   VALIDATE STATE AND POSTCODE
   --------------------------------------------------------- */

$allowed_states = [
    "VIC",
    "NSW",
    "QLD",
    "NT",
    "WA",
    "SA",
    "TAS",
    "ACT"
];

if (!in_array($state, $allowed_states, true)) {
    $errors[] = "Please select a valid Australian state.";
}

if ($postcode === "") {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must contain exactly four digits.";
} elseif (
    in_array($state, $allowed_states, true)
    && !postcode_matches_state($postcode, $state)
) {
    $errors[] =
        "The postcode does not match the selected state.";
}

/* ---------------------------------------------------------
   VALIDATE CONTACT DETAILS
   --------------------------------------------------------- */

if ($email === "") {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($phone === "") {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] =
        "Phone number must contain between 8 and 12 digits.";
}

/* ---------------------------------------------------------
   VALIDATE SKILLS
   --------------------------------------------------------- */

$allowed_skills = [
    "HTML and CSS",
    "PHP",
    "MySQL",
    "UI and UX Design",
    "Content Creation",
    "Digital Accessibility",
    "Other"
];

$valid_skills = [];

if (!is_array($submitted_skills)) {
    $submitted_skills = [];
}

foreach ($submitted_skills as $skill) {
    $skill = clean_input((string) $skill);

    if (in_array($skill, $allowed_skills, true)) {
        $valid_skills[] = $skill;
    }
}

$valid_skills = array_unique($valid_skills);

if (count($valid_skills) === 0) {
    $errors[] = "Please select at least one skill.";
}

if (
    in_array("Other", $valid_skills, true)
    && $other_skills === ""
) {
    $errors[] =
        "Please describe your other skills when Other is selected.";
}

if (strlen($other_skills) > 500) {
    $errors[] =
        "Other skills cannot exceed 500 characters.";
}

$skills = implode(", ", $valid_skills);

/* ---------------------------------------------------------
   CONNECT TO DATABASE
   --------------------------------------------------------- */

$conn = mysqli_connect(
    $host,
    $user,
    $pwd,
    $sql_db
);

if (!$conn) {
    $errors[] =
        "The application could not connect to the database.";
} else {
    mysqli_set_charset($conn, "utf8mb4");
}

/* ---------------------------------------------------------
   CHECK THAT JOB EXISTS
   --------------------------------------------------------- */

if (
    empty($errors)
    && $conn
) {
    $job_sql = "
        SELECT job_ref
        FROM jobs
        WHERE job_ref = ?
        LIMIT 1
    ";

    $job_stmt = mysqli_prepare($conn, $job_sql);

    if (!$job_stmt) {
        $errors[] =
            "The job reference could not be checked.";
    } else {
        mysqli_stmt_bind_param(
            $job_stmt,
            "s",
            $job_ref
        );

        mysqli_stmt_execute($job_stmt);

        $job_result =
            mysqli_stmt_get_result($job_stmt);

        if (
            !$job_result
            || mysqli_num_rows($job_result) === 0
        ) {
            $errors[] =
                "The selected job reference does not exist.";
        }

        if ($job_result) {
            mysqli_free_result($job_result);
        }

        mysqli_stmt_close($job_stmt);
    }
}

/* ---------------------------------------------------------
   INSERT VALID APPLICATION
   --------------------------------------------------------- */

$eoi_number = null;

if (
    empty($errors)
    && $conn
) {
    $status = "New";

    $insert_sql = "
        INSERT INTO eoi (
            job_ref,
            first_name,
            last_name,
            date_of_birth,
            gender,
            street_address,
            suburb_town,
            state,
            postcode,
            email,
            phone,
            skills,
            other_skills,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $insert_stmt =
        mysqli_prepare($conn, $insert_sql);

    if (!$insert_stmt) {
        $errors[] =
            "The application could not be prepared for saving.";
    } else {
        mysqli_stmt_bind_param(
            $insert_stmt,
            "ssssssssssssss",
            $job_ref,
            $first_name,
            $last_name,
            $date_of_birth_database,
            $gender,
            $street_address,
            $suburb_town,
            $state,
            $postcode,
            $email,
            $phone,
            $skills,
            $other_skills,
            $status
        );

        if (mysqli_stmt_execute($insert_stmt)) {
            $eoi_number =
                mysqli_insert_id($conn);
        } else {
            $errors[] =
                "The application could not be saved. Please try again.";
        }

        mysqli_stmt_close($insert_stmt);
    }
}

if ($conn) {
    mysqli_close($conn);
}

/* ---------------------------------------------------------
   PAGE CONTENT
   --------------------------------------------------------- */

$page_title = empty($errors)
    ? "Nexora | Application Submitted"
    : "Nexora | Application Error";

$body_class = "application-result-page";
$current_page = "apply";

include("header.inc");
include("nav.inc");
?>

<main class="form-container">

    <?php if (!empty($errors)): ?>

        <section class="error-banner" role="alert">
            <h1>Application could not be submitted</h1>

            <p>
                Please correct the following issue(s):
            </p>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?php
                        echo htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p>
                <a
                    href="apply.php?job_ref=<?php
                    echo urlencode($job_ref);
                    ?>"
                    class="btn-submit"
                >
                    Return to Application Form
                </a>
            </p>
        </section>

    <?php else: ?>

        <section class="success-banner" role="status">
            <h1>Application submitted successfully</h1>

            <p>
                Thank you,
                <strong>
                    <?php
                    echo htmlspecialchars(
                        $first_name . " " . $last_name,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </strong>.
            </p>

            <p>
                Your application for
                <strong>
                    <?php
                    echo htmlspecialchars(
                        $job_ref,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </strong>
                has been received.
            </p>

            <p>
                Your EOI number is:
                <strong>
                    <?php
                    echo htmlspecialchars(
                        (string) $eoi_number,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </strong>
            </p>

            <p>
                Please keep this number for your records.
            </p>

            <p>
                <a href="jobs.php" class="btn-submit">
                    Return to Jobs
                </a>
            </p>
        </section>

    <?php endif; ?>

</main>

<?php include("footer.inc"); ?>