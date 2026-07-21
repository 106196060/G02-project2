<?php
/*
    COS10026 Web Technology
    Project Part 2

    Home page developed for the Nexora website.
*/

$page_title = "Nexora | Home";
$body_class = "home";
$current_page = "home";

include("header.inc");
include("nav.inc");
?>

<main id="main">

    <section class="home-hero">

        <div class="home-hero-content">

            <p class="eyebrow">TECHNOLOGY FOR COMMUNITY IMPACT</p>

            <h1>
                Building stronger digital futures together.
            </h1>

            <p class="home-introduction">
                Nexora is a non-profit organisation that helps communities
                develop digital skills, access technology and participate in
                inclusive digital opportunities.
            </p>

            <p>
                We connect volunteers, job applicants and community members
                with meaningful opportunities that use technology to create
                positive change.
            </p>

            <div class="home-actions">
                <a href="jobs.php" class="primary-button">
                    Explore Opportunities
                </a>

                <a href="about.php" class="secondary-button">
                    Learn About Nexora
                </a>
            </div>

        </div>

        <figure class="home-hero-figure">
            <img
                src="styles/images/nexora_company_image.png"
                alt="Nexora volunteers supporting community members with digital technology"
                class="hero-image"
            >

            <figcaption>
                Supporting communities through accessible technology and
                digital learning.
            </figcaption>
        </figure>

    </section>

    <section class="home-purpose">

        <h2>What We Do</h2>

        <div class="purpose-cards">

            <article>
                <h3>Digital Support</h3>

                <p>
                    We help community members confidently use online services,
                    digital platforms and everyday technology.
                </p>
            </article>

            <article>
                <h3>Inclusive Opportunities</h3>

                <p>
                    We provide employment and volunteering opportunities for
                    people who want to create meaningful community impact.
                </p>
            </article>

            <article>
                <h3>Community Learning</h3>

                <p>
                    We deliver practical workshops and accessible resources
                    that improve digital skills and confidence.
                </p>
            </article>

        </div>

    </section>

    <section class="home-callout">

        <div>
            <h2>Ready to make an impact?</h2>

            <p>
                Browse our available roles and submit an Expression of Interest
                to join the Nexora team.
            </p>
        </div>

        <a href="apply.php" class="primary-button">
            Apply Now
        </a>

    </section>

</main>

<?php include("footer.inc"); ?>