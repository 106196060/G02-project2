<?php
$page_title = "Nexora | Apply";
$body_class = "apply-page";
$current_page = "apply";

/*
 * A job reference can be passed from jobs.php, for example:
 * apply.php?job_ref=NX001
 */
$selected_job_ref = strtoupper(trim($_GET["job_ref"] ?? ""));

include("header.inc");
include("nav.inc");
?>

<main class="form-container">

    <section class="form-introduction">
        <p class="eyebrow">JOIN NEXORA</p>

        <h1>Job Application Form</h1>

        <p>
            Complete the form below to submit your Expression of Interest.
            All fields marked as required must be completed correctly.
        </p>
    </section>

    <form
        action="process_eoi.php"
        method="post"
        class="application-form"
        novalidate
    >

        <fieldset>
            <legend>Position Details</legend>

            <div class="form-group">
                <label for="job-ref">
                    Job Reference Number
                </label>

                <input
                    type="text"
                    id="job-ref"
                    name="job_ref"
                    maxlength="5"
                    value="<?php echo htmlspecialchars($selected_job_ref); ?>"
                    placeholder="Example: NX001"
                >

                <span class="hint">
                    Enter the five-character reference shown on the jobs page.
                </span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Personal Information</legend>

            <div class="form-group">
                <label for="first-name">
                    First Name
                </label>

                <input
                    type="text"
                    id="first-name"
                    name="first_name"
                    maxlength="20"
                >

                <span class="hint">
                    Maximum 20 alphabetic characters.
                </span>
            </div>

            <div class="form-group">
                <label for="last-name">
                    Last Name
                </label>

                <input
                    type="text"
                    id="last-name"
                    name="last_name"
                    maxlength="20"
                >

                <span class="hint">
                    Maximum 20 alphabetic characters.
                </span>
            </div>

            <div class="form-group">
                <label for="date-of-birth">
                    Date of Birth
                </label>

                <input
                    type="text"
                    id="date-of-birth"
                    name="date_of_birth"
                    placeholder="dd/mm/yyyy"
                >

                <span class="hint">
                    Format: dd/mm/yyyy
                </span>
            </div>

            <fieldset class="sub-fieldset">
                <legend>Gender</legend>

                <div class="radio-group">
                    <label>
                        <input
                            type="radio"
                            name="gender"
                            value="Male"
                        >
                        Male
                    </label>

                    <label>
                        <input
                            type="radio"
                            name="gender"
                            value="Female"
                        >
                        Female
                    </label>

                    <label>
                        <input
                            type="radio"
                            name="gender"
                            value="Other"
                        >
                        Other
                    </label>
                </div>
            </fieldset>
        </fieldset>

        <fieldset>
            <legend>Address Details</legend>

            <div class="form-group">
                <label for="street-address">
                    Street Address
                </label>

                <input
                    type="text"
                    id="street-address"
                    name="street_address"
                    maxlength="40"
                >

                <span class="hint">
                    Maximum 40 characters.
                </span>
            </div>

            <div class="form-group">
                <label for="suburb-town">
                    Suburb or Town
                </label>

                <input
                    type="text"
                    id="suburb-town"
                    name="suburb_town"
                    maxlength="40"
                >

                <span class="hint">
                    Maximum 40 characters.
                </span>
            </div>

            <div class="form-group">
                <label for="state">
                    State
                </label>

                <select id="state" name="state">
                    <option value="">
                        Please select
                    </option>

                    <option value="VIC">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="NT">NT</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                </select>
            </div>

            <div class="form-group">
                <label for="postcode">
                    Postcode
                </label>

                <input
                    type="text"
                    id="postcode"
                    name="postcode"
                    maxlength="4"
                >

                <span class="hint">
                    Enter exactly four digits.
                </span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Contact Details and Skills</legend>

            <div class="form-group">
                <label for="email">
                    Email Address
                </label>

                <input
                    type="text"
                    id="email"
                    name="email"
                >

                <span class="hint">
                    Enter a valid email address.
                </span>
            </div>

            <div class="form-group">
                <label for="phone">
                    Phone Number
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    maxlength="12"
                >

                <span class="hint">
                    Enter between 8 and 12 digits.
                </span>
            </div>

            <fieldset class="sub-fieldset">
                <legend>Skills</legend>

                <div class="checkbox-group">
                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="HTML and CSS"
                        >
                        HTML and CSS
                    </label>

                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="PHP"
                        >
                        PHP
                    </label>

                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="MySQL"
                        >
                        MySQL
                    </label>

                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="UI and UX Design"
                        >
                        UI and UX Design
                    </label>

                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="Content Creation"
                        >
                        Content Creation
                    </label>

                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="Digital Accessibility"
                        >
                        Digital Accessibility
                    </label>

                    <label>
                        <input
                            type="checkbox"
                            name="skills[]"
                            value="Other"
                        >
                        Other
                    </label>
                </div>
            </fieldset>

            <div class="form-group">
                <label for="other-skills">
                    Other Skills
                </label>

                <textarea
                    id="other-skills"
                    name="other_skills"
                    rows="5"
                    placeholder="Describe any other relevant skills"
                ></textarea>
            </div>
        </fieldset>

        <button
            type="submit"
            class="btn-submit"
        >
            Submit Application
        </button>

    </form>

</main>

<?php include("footer.inc"); ?>