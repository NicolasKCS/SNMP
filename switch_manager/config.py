import os

class Config:
    SECRET_KEY = os.urandom(32)
    MYSQL_HOST = 'localhost'
    MYSQL_USER = 'root'
    MYSQL_PASSWORD = 'admin'
    MYSQL_DB = 'switch_management'
