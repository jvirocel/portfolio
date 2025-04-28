-- Create the database
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Create projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create messages table for contact form
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample projects
INSERT INTO projects (title, description, link) VALUES
('Project 1', 'A web application built with PHP and MySQL', 'https://github.com/yourusername/project1'),
('Project 2', 'A responsive website using HTML5 and CSS3', 'https://github.com/yourusername/project2'),
('Project 3', 'A database-driven application', 'https://github.com/yourusername/project3'); 