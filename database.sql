CREATE DATABASE IF NOT EXISTS job_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE job_app;

CREATE TABLE IF NOT EXISTS job_applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    id_number VARCHAR(50) NOT NULL,
    cellphone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    employment_equity VARCHAR(50) NOT NULL,
    availability VARCHAR(50) NOT NULL,
    qualification_type VARCHAR(100) NOT NULL,
    qualification VARCHAR(150) NOT NULL,
    institution VARCHAR(150) NOT NULL,
    qualification_year VARCHAR(10) NOT NULL,
    qualification_status VARCHAR(50) NOT NULL,
    salary_type VARCHAR(50) NOT NULL,
    salary_rate VARCHAR(50) NOT NULL,
    sector VARCHAR(100) NOT NULL,
    `function` VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL,
    cv_filename VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_job_applications_email (email),
    INDEX idx_job_applications_id_number (id_number),
    INDEX idx_job_applications_created_at (created_at)
);
