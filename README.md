# SNMP

python3 -m venv venv
pip install flask flask-mysqldb PySNMP

###SQL

CREATE DATABASE switch_management;

USE switch_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE ports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    port_number INT NOT NULL,
    status ENUM('open', 'closed') NOT NULL
);

### Estrutura de diretorio
/switch_manager
    /static
    /templates
    app.py
    config.py
