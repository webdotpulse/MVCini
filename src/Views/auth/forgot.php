<div style="max-width: 400px; margin: auto;">
    <h2>Forgot Password</h2>
    <?php if(!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
    <?php endif; ?>
    <?php if(!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars((string)$success) ?></p>
    <?php endif; ?>

    <p>Enter your email address and we will send you a link to reset your password.</p>

    <form method="POST" action="/auth/forgot">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn">Send Reset Link</button>
        <a href="/auth/login" style="margin-left: 10px;">Back to Login</a>
    </form>
</div>
