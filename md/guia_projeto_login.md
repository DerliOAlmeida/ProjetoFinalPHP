# 📘 Guia Prático: Sistema de Login Seguro em PHP

## ✅ Estrutura do Projeto
```
/projeto-login
│ index.php
│ cadastro.php
│ recuperar.php
│ dashboard.php
│ logout.php
├── includes/
│    ├── db_connect.php
│    ├── auth.php
│    ├── mailer.php
├── assets/
│    ├── style.css
│    ├── script.js
└── sql/
     └── schema.sql
```

## ✅ Funcionalidades
- Login com CSRF e reCAPTCHA
- Cadastro com hash de senha
- Recuperação de senha via e-mail
- Controle de sessão

## ✅ Código comentado
(Exemplos completos de index.php, cadastro.php, recuperar.php, auth.php, mailer.php)

## ✅ Segurança
- CSRF Token
- reCAPTCHA
- password_hash()
- Prepared Statements

## ✅ Instalação
1. Instale XAMPP
2. Importe sql/schema.sql
3. Configure PHPMailer e reCAPTCHA

## 🔗 Links úteis
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- [Google reCAPTCHA](https://www.google.com/recaptcha/admin)
- [Bootstrap](https://getbootstrap.com)
