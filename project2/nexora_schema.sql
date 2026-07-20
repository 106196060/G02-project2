/* =========================================================
   NEXORA PROJECT 2 DATABASE
   Database: nexora_db
   ========================================================= */

CREATE DATABASE IF NOT EXISTS nexora_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE nexora_db;


/* =========================================================
   1. JOBS TABLE — ANGELA
   ========================================================= */

CREATE TABLE IF NOT EXISTS jobs (
    job_ref VARCHAR(5) PRIMARY KEY,
    job_title VARCHAR(100) NOT NULL,
    organisation VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    employment_type VARCHAR(50) NOT NULL,
    work_type VARCHAR(50) NOT NULL,
    salary VARCHAR(100) NOT NULL,
    summary TEXT NOT NULL,
    icon VARCHAR(255) DEFAULT NULL,
    icon_colour VARCHAR(50) DEFAULT NULL,
    reporting_line VARCHAR(255) NOT NULL,
    responsibilities TEXT NOT NULL,
    essential_requirements TEXT NOT NULL,
    preferable_requirements TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/* Insert or update the two Nexora jobs */

INSERT INTO jobs (
    job_ref,
    job_title,
    organisation,
    location,
    employment_type,
    work_type,
    salary,
    summary,
    icon,
    icon_colour,
    reporting_line,
    responsibilities,
    essential_requirements,
    preferable_requirements
)
VALUES
(
    'NX001',
    'Digital Support Coordinator',
    'Nexora',
    'Doha, Qatar',
    'Volunteer',
    'Hybrid and Flexible',
    'Volunteer Position',
    'Support Nexora volunteers and community members by coordinating digital tools, responding to technical enquiries and helping the organisation use technology effectively.',
    'images/nexora_asset_pack_revised/icons/leaf.svg',
    'green',
    'Reports to the Technology and Operations Manager.',
    'Provide basic digital support to volunteers and staff; assist with account and platform access; maintain simple technical documentation; coordinate technical requests; support online meetings and digital events; report recurring technical problems.',
    'Basic understanding of computers and online platforms; good communication and organisation skills; ability to work responsibly in a team; willingness to learn new digital tools.',
    'Experience with help-desk support, Microsoft 365, Google Workspace, content-management systems or volunteer organisations.'
),
(
    'NX002',
    'Web and Content Assistant',
    'Nexora',
    'Doha, Qatar',
    'Volunteer',
    'Hybrid and Flexible',
    'Volunteer Position',
    'Help maintain Nexora''s website and digital content by updating pages, preparing accessible content and supporting the organisation''s online presence.',
    'images/nexora_asset_pack_revised/icons/laptop.svg',
    'blue',
    'Reports to the Communications and Digital Media Manager.',
    'Update website text and images; assist with creating accessible web content; check pages for broken links and formatting issues; prepare simple digital resources; support social-media and campaign content; work with the technology and communications teams.',
    'Basic HTML and CSS knowledge; clear written communication; attention to detail; basic image and document editing skills; ability to meet agreed deadlines.',
    'Experience with Canva, WordPress, GitHub, accessibility checking, social-media content or non-profit projects.'
)
ON DUPLICATE KEY UPDATE
    job_title = VALUES(job_title),
    organisation = VALUES(organisation),
    location = VALUES(location),
    employment_type = VALUES(employment_type),
    work_type = VALUES(work_type),
    salary = VALUES(salary),
    summary = VALUES(summary),
    icon = VALUES(icon),
    icon_colour = VALUES(icon_colour),
    reporting_line = VALUES(reporting_line),
    responsibilities = VALUES(responsibilities),
    essential_requirements = VALUES(essential_requirements),
    preferable_requirements = VALUES(preferable_requirements);


/* =========================================================
   2. USERS TABLE — DHUWA
   The marker logs in using:
   Username: Admin
   Password: Admin

   The password stored below is hashed.
   login.php must use password_verify().
   ========================================================= */

CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/* Password hash represents the password: Admin */

INSERT INTO users (
    username,
    email,
    password,
    role
)
VALUES (
    'Admin',
    'admin@nexora.com',
    '$2y$12$wP0w1r02tc3U6t4iDU1Iee33sBDHtK2Toj.e3DWxZITrp/9.h2jKy',
    'admin'
)
ON DUPLICATE KEY UPDATE
    email = VALUES(email),
    password = VALUES(password),
    role = VALUES(role);


/* =========================================================
   3. EOI TABLE — ELIJAH
   Required status values:
   New, Current, Final

   New applications automatically receive status New.
   ========================================================= */

CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    job_ref VARCHAR(5) NOT NULL,

    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,

    street_address VARCHAR(40) NOT NULL,
    suburb_town VARCHAR(40) NOT NULL,
    state VARCHAR(3) NOT NULL,
    postcode VARCHAR(4) NOT NULL,

    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,

    skills TEXT NOT NULL,
    other_skills TEXT DEFAULT NULL,

    status ENUM('New', 'Current', 'Final')
        NOT NULL DEFAULT 'New',

    submitted_at TIMESTAMP
        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_eoi_job_ref (job_ref),
    INDEX idx_eoi_first_name (first_name),
    INDEX idx_eoi_last_name (last_name),
    INDEX idx_eoi_status (status),

    CONSTRAINT fk_eoi_job
        FOREIGN KEY (job_ref)
        REFERENCES jobs(job_ref)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/* =========================================================
   4. ABOUT / MEMBER CONTRIBUTIONS TABLE — FAISAL
   Each person must update the descriptions to match the
   work they actually completed.
   ========================================================= */

CREATE TABLE IF NOT EXISTS about (
    member_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_name VARCHAR(100) NOT NULL UNIQUE,
    student_id VARCHAR(30) DEFAULT NULL,
    member_role VARCHAR(100) DEFAULT NULL,
    project1_contribution TEXT NOT NULL,
    project2_contribution TEXT NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/* Replace student IDs and adjust contribution wording if needed */

INSERT INTO about (
    member_name,
    student_id,
    member_role,
    project1_contribution,
    project2_contribution,
    profile_image
)
VALUES
(
    'Angela',
    NULL,
    'Jobs Page and Database Developer',
    'Created the jobs page, job descriptions, job cards and position-detail sections for Project 1. Contributed to the shared design, navigation and testing of the Nexora website.',
    'Created the jobs database table, inserted the job records and converted jobs.php into a dynamic database-driven page. Assisted with database integration, testing and final project organisation.',
    NULL
),
(
    'Dhuwa',
    NULL,
    'Authentication and Project Coordination',
    'Contributed to project coordination, GitHub organisation, Jira planning, shared navigation and testing during Project 1.',
    'Created the users table and developed the login and authentication system used to protect the HR management page. Assisted with integration and testing.',
    NULL
),
(
    'Faisal',
    NULL,
    'About Page Developer',
    'Contributed to the home page, about page, shared styling and website content during Project 1.',
    'Created the about database content and converted about.php to load member contributions dynamically from the database. Assisted with shared page integration.',
    NULL
),
(
    'Elijah',
    NULL,
    'Application and EOI Developer',
    'Created and styled the job application form during Project 1 and contributed to form content and testing.',
    'Created the EOI database functionality and process_eoi.php. Implemented server-side validation, sanitisation, database insertion and confirmation of the generated EOI number.',
    NULL
)
ON DUPLICATE KEY UPDATE
    student_id = VALUES(student_id),
    member_role = VALUES(member_role),
    project1_contribution = VALUES(project1_contribution),
    project2_contribution = VALUES(project2_contribution),
    profile_image = VALUES(profile_image);