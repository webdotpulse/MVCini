<div style="max-width: 400px; margin: auto;">
    <h2>Register</h2>
    <?php if(!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
    <?php endif; ?>
    <?php if(!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars((string)$success) ?></p>
    <?php endif; ?>
    <form method="POST" action="/auth/register">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn">Register</button>
        <a href="/auth/login" style="margin-left: 10px;">Login</a>
    </form>
</div>
