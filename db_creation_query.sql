CREATE DATABASE IF NOT EXISTS licCheck;

USE licCheck;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE role_permissions (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

INSERT INTO roles (role_name) VALUES ('Administrator'), ('User'), ('Guest');

INSERT INTO permissions (permission_name) VALUES ('CREATE_USER'), ('EDIT_USER'), ('DELETE_USER'), ('VIEW_USER');

INSERT INTO users (username,password,email) VALUES ('admin','$2y$10$j6SPOOVZ4puJJ9BI.Ed.cuIFvsNNEIWIvkIHvqQw9vqRPnQ29q1he','admin@admin.com');
INSERT INTO user_roles (user_id,role_id) VALUES (1,1);
-- admin login:
-- login: admin@admin.com
-- pass: admin

INSERT INTO users (username,password,email) VALUES ('user1','$2y$10$1jlC2NpKDfl5SqfEw6QPhuk4N/qTqtsMXEAzStUFer1dp7rzfubwS','user1@user1.com');
INSERT INTO user_roles (user_id,role_id) VALUES (2,2);
-- user login:
-- login: user1@user1.com
-- pass: user1

CREATE TABLE cad_systems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cad_name VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    port INT NOT NULL,
    server VARCHAR(255) NOT NULL,
    product VARCHAR(255) NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cad_systems (cad_name,display_name,port,server,product,active) VALUES ('autocad','AutoCAD DEMO','27000','server.example.com','acad',TRUE);
INSERT INTO cad_systems (cad_name,display_name,port,server,product,active) VALUES ('inventor','Inventor DEMO','27000','server.example.com','inv',TRUE);
INSERT INTO cad_systems (cad_name,display_name,port,server,product,active) VALUES ('solidworks','SolidWorks DEMO','25734','server.example.com','solidworks',TRUE);
INSERT INTO cad_systems (cad_name,display_name,port,server,product,active) VALUES ('eplan','EPLAN DEMO','59215','server.example.com','eplan',TRUE);
