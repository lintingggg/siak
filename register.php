<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="src/css/register.css">
    <title>Register</title>
</head>
<body>
    <div class="register-box">
        <div class="register-header">
            <header>Register</header>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" id="username" required>
            <label for="username">Username</label>
        </div>
        <div class="input-box">
            <input type="email" class="input-field" id="email" required>
            <label for="email">Email</label>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" id="password" required>
            <label for="password">Password</label>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" id="confirm-password" required>
            <label for="confirm-password">Confirm Password</label>
        </div>
        <div class="input-box">
            <input type="submit" class="input-submit" id="Register">
        </div>
        <div class="sign-up">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
