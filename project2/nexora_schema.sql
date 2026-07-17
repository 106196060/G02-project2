CREATE DATABASE IF NOT EXISTS nexora_db;
USE nexora_db;

CREATE TABLE IF NOT EXISTS jobs (
    job_ref VARCHAR(5) PRIMARY KEY,
    job_title VARCHAR(100) NOT NULL,
    organisation VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    employment_type VARCHAR(50) NOT NULL,
    work_type VARCHAR(50) NOT NULL,
    salary VARCHAR(100) NOT NULL,
    summary TEXT NOT NULL,
    icon VARCHAR(255),
    icon_colour VARCHAR(50),
    reporting_line VARCHAR(255) NOT NULL,
    responsibilities TEXT NOT NULL,
    essential_requirements TEXT NOT NULL,
    preferable_requirements TEXT NOT NULL
);