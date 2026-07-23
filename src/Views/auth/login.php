<div style="max-width: 400px; margin: auto;">
    <h2>Login</h2>
    <?php if(!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
    <?php endif; ?>
    <form method="POST" action="/auth/login">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
        <div class="form-group">
            <label>Username or Email:</label>
            <input type="text" name="identifier" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"> Remember Me</label>
        </div>
        <button type="submit" class="btn">Login</button>
        <a href="/auth/register" style="margin-left: 10px;">Register</a>
        <br><br>
        <a href="/auth/forgot" style="font-size: 0.9em;">Forgot your password?</a>
    </form>
</div>
