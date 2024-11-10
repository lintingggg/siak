<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="src/css/login.css">
    <title>login</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Login</header>
                
        </div>
        <div class="input-box">
            <input type="text" class="input-field" id="email">
            <label for="email">email</label>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" id="password">
            <label for="password">password</label>
        </div>
        <div class="forgot">
            <section>
                <input type="checkbox" id="check">
                <label for="check">Remember me</label>
            </section>
            <section>
                <a href="#" class="forgot-link">Forgot password?</a>
            </section>
        </div>
        <div class="input-box">
            <input type="submit" class="input-submit" id="Login">
        </div>
        <div class="sing-up">
            <p>Don't have account <a href="register.php">Sign up</a></p>
        </div>
    </div>
</body>
</html>