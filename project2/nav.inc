<?php
/*
    Shared navigation menu.
    The current page is highlighted using the active class.
*/

if (!isset($current_page)) {
    $current_page = "";
}
?>

<header class="site-header">

    <a href="index.php" class="brand-link">

        <img
            src="images/nexora_asset_pack_revised/logo/nexora-icon-only.svg"
            alt="Nexora logo"
            class="brand-logo"
        >

        <span>Nexora</span>

    </a>

    <nav class="main-nav" aria-label="Main navigation">

        <a
            href="index.php"
            class="<?php echo ($current_page === "home") ? "active" : ""; ?>"
        >
            Home
        </a>

        <a
            href="jobs.php"
            class="<?php echo ($current_page === "jobs") ? "active" : ""; ?>"
        >
            Jobs
        </a>

        <a
            href="apply.php"
            class="<?php echo ($current_page === "apply") ? "active" : ""; ?>"
        >
            Apply
        </a>

        <a
            href="about.php"
            class="<?php echo ($current_page === "about") ? "active" : ""; ?>"
        >
            About
        </a>

        <a
             href="manage.php"
             class="<?php echo $current_page === 'manage' ? 'active' : ''; ?>"
        >
             Manage
</a>

    </nav>

    <a href="apply.php" class="nav-button">

        Get Involved

        <img
            src="styles/images/heart.png"
            alt=""
        >

    </a>

</header>