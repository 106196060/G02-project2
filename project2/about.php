<?php
require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

$query = "SELECT * FROM about ORDER BY member_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error loading team members: " . mysqli_error($conn));
}

$members = [];

while ($member = mysqli_fetch_assoc($result)) {
    $members[] = $member;
}

$page_title = "About - Nexora";
$body_class = "about-page";
$current_page = "about";

include("header.inc");
include("nav.inc");
?>

<main>

    <h1>Team Profile: Nexora</h1>

    <section>
        <h2>Group Information</h2>

        <ul>
            <li>
                Group Name: Nexora

                <ul>
                    <li>Class Day/Time: Sunday–Monday, 11 AM to 3 PM</li>
                    <li>Break: 12 PM to 1 PM</li>
                </ul>
            </li>
        </ul>
    </section>

    <section>
        <h2>Member Contributions</h2>

        <?php if (!empty($members)): ?>

            <div class="team-members">

                <?php foreach ($members as $member): ?>

                    <article class="team-member-card">

                        <?php if (!empty($member["profile_image"])): ?>

                            <img
                                src="<?= htmlspecialchars($member["profile_image"]) ?>"
                                alt="<?= htmlspecialchars($member["member_name"]) ?>"
                                class="member-profile-image"
                            >

                        <?php endif; ?>

                        <h3>
                            <?= htmlspecialchars($member["member_name"]) ?>
                        </h3>

                        <?php if (!empty($member["student_id"])): ?>

                            <p>
                                <strong>Student ID:</strong>
                                <?= htmlspecialchars($member["student_id"]) ?>
                            </p>

                        <?php endif; ?>

                        <p>
                            <strong>Role:</strong>
                            <?= htmlspecialchars($member["member_role"]) ?>
                        </p>

                        <p>
                            <strong>Project 1 Contribution:</strong>
                            <?= htmlspecialchars($member["project1_contribution"]) ?>
                        </p>

                        <p>
                            <strong>Project 2 Contribution:</strong>
                            <?= htmlspecialchars($member["project2_contribution"]) ?>
                        </p>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p>No team members were found.</p>

        <?php endif; ?>

    </section>

    <section>
        <h2>Group Photo</h2>

        <figure>
            <img
                src="styles/images/WhatsApp Image 2026-06-28 at 6.33.07 PM.jpeg"
                alt="The Nexora project team"
                width="400"
            >

            <figcaption>Our Nexora project team</figcaption>
        </figure>
    </section>

    <section>
        <h2>Fun Facts</h2>

        <table>
            <caption>Team Fun Facts</caption>

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Dream Job</th>
                    <th>Coding Snack</th>
                    <th>Hometown</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Faisal</td>
                    <td>Venue Operations Manager</td>
                    <td>Mixed Nuts</td>
                    <td>Jaffa, Palestine</td>
                </tr>

                <tr>
                    <td>Angela</td>
                    <td>Doctor</td>
                    <td>Chocolate and ice cream</td>
                    <td>Quezon City, Philippines / Barcelona, Spain</td>
                </tr>

                <tr>
                    <td>Elijah</td>
                    <td>Chef</td>
                    <td>Watermelon</td>
                    <td>Manila, Philippines</td>
                </tr>

                <tr>
                    <td>Dhuwa</td>
                    <td>Cybersecurity Researcher</td>
                    <td>Chocolate</td>
                    <td>Doha, Qatar</td>
                </tr>
            </tbody>
        </table>
    </section>

</main>

<?php
mysqli_free_result($result);
mysqli_close($conn);

include("footer.inc");
?>