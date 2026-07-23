<h2>Contact Us</h2>

<?php if(!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
<?php endif; ?>

<?php if(!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars((string)$success) ?></p>
<?php endif; ?>

<form method="POST" action="/contact/index">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">

    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Message:</label>
        <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>

    <div class="form-group">
        <label>Solve this: <img src="/contact/captcha" alt="Captcha" style="vertical-align: middle; margin-left: 10px;"></label>
        <input type="number" name="captcha" class="form-control" style="width: 100px; display: inline-block;" required>
    </div>

    <button type="submit" class="btn">Send Message</button>
</form>
