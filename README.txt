PharmaBot PHP conversion ZIP

Instructions:

1) Copy this folder into your web server document root (e.g., XAMPP htdocs or WAMP www).
2) Put your trained model file update_medicine_classifier_multiclass.h5 into the ocr/ folder.
3) Edit includes/config.php if python executable differs (e.g., 'python' on Windows).
4) Ensure PHP can execute shell commands (shell_exec). For Windows, use 'python' and ensure python is in PATH.
5) Run http://localhost/pharmabot_php/setup_demo.php to create the database and demo users.
6) Login at http://localhost/pharmabot_php/login.php with admin@pharmabot.com / admin123
7) Test scanning via Scan Medicine page.
