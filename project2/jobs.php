<?php
require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

/*
 * Looks for an image in both image folders.
 * If the database says leaf.svg but only leaf.png exists,
 * it will also try the other extension.
 */
function find_image_path(string $image_name): ?string
{
    $image_name = basename(trim($image_name));

    if ($image_name === "") {
        return null;
    }

    $base_name = pathinfo($image_name, PATHINFO_FILENAME);

    $possible_files = [
        "styles/images/" . $image_name,
        "images/" . $image_name,
        "styles/images/" . $base_name . ".png",
        "styles/images/" . $base_name . ".svg",
        "images/" . $base_name . ".png",
        "images/" . $base_name . ".svg"
    ];

    foreach ($possible_files as $file) {
        if (is_file(__DIR__ . "/" . $file)) {
            return $file;
        }
    }

    return null;
}

$query = "SELECT * FROM jobs ORDER BY job_ref";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error loading jobs: " . mysqli_error($conn));
}

/*
 * Store jobs in an array because they are used more than once:
 * 1. Main job cards
 * 2. Sidebar links
 * 3. Position detail sections
 */
$jobs = [];

while ($job = mysqli_fetch_assoc($result)) {
    $jobs[] = $job;
}

mysqli_free_result($result);

$job_count = count($jobs);

$page_title = "Nexora | Careers";
$body_class = "jobs-page";
$current_page = "jobs";

include("header.inc");
include("nav.inc");
?>

<main>

    <section class="jobs-hero">

        <div class="jobs-hero-text">

            <p class="eyebrow">
                CAREERS WITH PURPOSE
            </p>

            <h1>
                Find your next<br>
                <span>impact opportunity.</span>
            </h1>

            <p class="hero-description">
                Join Nexora and help communities build stronger digital futures
                through technology, support, and inclusive innovation.
            </p>

        </div>

        <div class="jobs-hero-visual">

            <aside class="why-card">

                <h2>Why Nexora?</h2>

                <p>
                    Technology can solve real problems and create a better
                    future for everyone.
                </p>

                <a
                    href="#job-list"
                    class="circle-arrow"
                    aria-label="View available roles"
                >
                    <img
                        src="styles/images/arrow-right.png"
                        alt=""
                    >
                </a>

            </aside>

        </div>

    </section>

    <section
        class="job-filter-section"
        aria-label="Role filters"
    >

        <div class="fake-search">

            <img
                src="styles/images/search.png"
                alt=""
            >

            <span>
                Search roles or keywords...
            </span>

        </div>

        <div class="filter-box">
            All Roles <span>⌄</span>
        </div>

        <div class="filter-box">
            All Locations <span>⌄</span>
        </div>

        <div class="filter-box">
            All Types <span>⌄</span>
        </div>

        <a
            href="#job-list"
            class="clear-filter"
        >
            Clear Filters
        </a>

    </section>

    <section
        class="jobs-content"
        id="job-list"
    >

        <div class="job-list-area">

            <?php if ($job_count > 0): ?>

                <?php foreach ($jobs as $job): ?>

                    <?php
                    $details_id =
                        "job-" .
                        strtolower($job["job_ref"]) .
                        "-details";

                    $essential_items = array_filter(
                        explode(
                            "|",
                            $job["essential_requirements"]
                        )
                    );

                    $skill_tags = array_slice(
                        $essential_items,
                        0,
                        3
                    );

                    $job_icon_path = find_image_path(
                        $job["icon"] ?? ""
                    );
                    ?>

                    <article class="job-card">

                        <div
                            class="job-icon <?= htmlspecialchars(
                                $job["icon_colour"]
                            ) ?>"
                        >

                            <?php if ($job_icon_path !== null): ?>

                                <img
                                    src="<?= htmlspecialchars(
                                        $job_icon_path
                                    ) ?>"
                                    alt=""
                                    aria-hidden="true"
                                >

                            <?php else: ?>

                                <span
                                    class="job-icon-fallback"
                                    aria-hidden="true"
                                >
                                    ✦
                                </span>

                            <?php endif; ?>

                        </div>

                        <div class="job-main-info">

                            <div class="job-heading-row">

                                <div>

                                    <p class="job-reference">
                                        REF:
                                        <?= htmlspecialchars(
                                            $job["job_ref"]
                                        ) ?>
                                    </p>

                                    <h2>
                                        <?= htmlspecialchars(
                                            $job["job_title"]
                                        ) ?>
                                    </h2>

                                </div>

                                <a
                                    href="apply.php?job_ref=<?= urlencode(
                                        $job["job_ref"]
                                    ) ?>"
                                    class="heart-button"
                                    aria-label="Apply for <?= htmlspecialchars(
                                        $job["job_title"]
                                    ) ?>"
                                >
                                    <img
                                        src="styles/images/heart.png"
                                        alt=""
                                    >
                                </a>

                            </div>

                            <p class="organisation-name">
                                <?= htmlspecialchars(
                                    $job["organisation"]
                                ) ?>
                            </p>

                            <div class="job-meta">

                                <span>
                                    📍
                                    <?= htmlspecialchars(
                                        $job["location"]
                                    ) ?>
                                </span>

                                <span>
                                    ◌
                                    <?= htmlspecialchars(
                                        $job["employment_type"]
                                    ) ?>
                                </span>

                                <span>
                                    ◷
                                    <?= htmlspecialchars(
                                        $job["work_type"]
                                    ) ?>
                                </span>

                            </div>

                            <p class="job-summary">
                                <?= htmlspecialchars(
                                    $job["summary"]
                                ) ?>
                            </p>

                            <?php if (!empty($skill_tags)): ?>

                                <div class="skill-tags">

                                    <?php foreach ($skill_tags as $tag): ?>

                                        <span>
                                            <?= htmlspecialchars(
                                                trim($tag)
                                            ) ?>
                                        </span>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                            <div class="job-card-bottom">

                                <p class="salary">
                                    Salary:
                                    <?= htmlspecialchars(
                                        $job["salary"]
                                    ) ?>
                                </p>

                                <a
                                    href="#<?= htmlspecialchars(
                                        $details_id
                                    ) ?>"
                                    class="view-details"
                                >
                                    View Details

                                    <img
                                        src="styles/images/arrow-right.png"
                                        alt=""
                                    >
                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

                <p class="job-count">
                    Showing
                    <?= $job_count ?>
                    of
                    <?= $job_count ?>
                    available opportunities
                </p>

            <?php else: ?>

                <p class="job-count">
                    There are currently no available opportunities.
                </p>

            <?php endif; ?>

        </div>

        <aside class="jobs-sidebar">

            <section class="sidebar-box">

                <h2>Explore Roles</h2>

                <?php foreach ($jobs as $job): ?>

                    <?php
                    $details_id =
                        "job-" .
                        strtolower($job["job_ref"]) .
                        "-details";
                    ?>

                    <a
                        href="#<?= htmlspecialchars(
                            $details_id
                        ) ?>"
                    >
                        <?= htmlspecialchars(
                            $job["job_title"]
                        ) ?>

                        <span>›</span>
                    </a>

                <?php endforeach; ?>

            </section>

            <section class="talent-box">

                <img
                    src="styles/images/users.png"
                    alt=""
                    class="talent-icon"
                >

                <h2>
                    Join our talent community
                </h2>

                <p>
                    Get early updates about future roles, workshops,
                    and digital opportunities.
                </p>

                <a
                    href="apply.php"
                    class="talent-button"
                >
                    Join Now

                    <img
                        src="styles/images/arrow-right.png"
                        alt=""
                    >
                </a>

            </section>

        </aside>

    </section>

    <section class="position-details">

        <?php foreach ($jobs as $job): ?>

            <?php
            $details_id =
                "job-" .
                strtolower($job["job_ref"]) .
                "-details";

            $responsibilities = array_filter(
                explode(
                    "|",
                    $job["responsibilities"]
                )
            );

            $essential_requirements = array_filter(
                explode(
                    "|",
                    $job["essential_requirements"]
                )
            );

            $preferable_requirements = array_filter(
                explode(
                    "|",
                    $job["preferable_requirements"]
                )
            );
            ?>

            <article
                class="detail-card"
                id="<?= htmlspecialchars(
                    $details_id
                ) ?>"
            >

                <p class="eyebrow">
                    POSITION DETAILS
                </p>

                <h2>
                    <?= htmlspecialchars(
                        $job["job_title"]
                    ) ?>
                </h2>

                <p>
                    <?= htmlspecialchars(
                        $job["summary"]
                    ) ?>
                </p>

                <div class="detail-grid">

                    <section>

                        <h3>Reporting line</h3>

                        <p>
                            <?= htmlspecialchars(
                                $job["reporting_line"]
                            ) ?>
                        </p>

                    </section>

                    <section>

                        <h3>Key responsibilities</h3>

                        <ol>

                            <?php foreach (
                                $responsibilities
                                as $responsibility
                            ): ?>

                                <li>
                                    <?= htmlspecialchars(
                                        trim($responsibility)
                                    ) ?>
                                </li>

                            <?php endforeach; ?>

                        </ol>

                    </section>

                    <section>

                        <h3>Essential requirements</h3>

                        <ul>

                            <?php foreach (
                                $essential_requirements
                                as $requirement
                            ): ?>

                                <li>
                                    <?= htmlspecialchars(
                                        trim($requirement)
                                    ) ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </section>

                    <section>

                        <h3>Preferable requirements</h3>

                        <ul>

                            <?php foreach (
                                $preferable_requirements
                                as $requirement
                            ): ?>

                                <li>
                                    <?= htmlspecialchars(
                                        trim($requirement)
                                    ) ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </section>

                </div>

                <a
                    href="apply.php?job_ref=<?= urlencode(
                        $job["job_ref"]
                    ) ?>"
                    class="primary-button"
                >
                    Apply for this role
                </a>

            </article>

        <?php endforeach; ?>

    </section>

</main>

<?php
mysqli_close($conn);
include("footer.inc");
?>