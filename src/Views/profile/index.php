<div style="max-width: 600px; margin: auto; text-align: center;">
    <h2>Profile: <?= htmlspecialchars((string)$user['username']) ?></h2>

    <?php if(!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
    <?php endif; ?>
    <?php if(!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars((string)$success) ?></p>
    <?php endif; ?>

    <div style="margin: 20px 0;">
        <img src="<?= htmlspecialchars((string)$avatarUrl) ?>" alt="Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
    </div>

    <p><strong>Email:</strong> <?= htmlspecialchars((string)$user['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars((string)$user['role']) ?></p>
    <p><strong>Status:</strong> <?= $user['is_verified'] ? 'Verified' : 'Unverified' ?></p>

    <hr style="margin: 30px 0;">

    <form method="POST" action="/profile/index" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
        <div class="form-group">
            <label>Upload New Avatar (JPG, PNG, GIF):</label>
            <input type="file" name="avatar" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn">Update Avatar</button>
    </form>
</div>
