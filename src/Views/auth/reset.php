<div style="max-width: 400px; margin: auto;">
    <h2>Reset Password</h2>
    <?php if(!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
    <?php endif; ?>
    <?php if(!empty($success)): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php else: ?>
        <form method="POST" action="/auth/reset">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars((string)$token) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars((string)$email) ?>">
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    <?php endif; ?>
</div>
