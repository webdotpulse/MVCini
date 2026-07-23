<h2><?= htmlspecialchars($title) ?></h2>

<form method="POST" action="/tools/calculator">
    <div class="form-group">
        <label>Number 1</label>
        <input type="number" step="any" name="num1" class="form-control" required value="<?= htmlspecialchars($_POST['num1'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Operation</label>
        <select name="op" class="form-control">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>
    </div>

    <div class="form-group">
        <label>Number 2</label>
        <input type="number" step="any" name="num2" class="form-control" required value="<?= htmlspecialchars($_POST['num2'] ?? '') ?>">
    </div>

    <button type="submit" class="btn">Calculate</button>
</form>

<?php if ($result !== ''): ?>
    <div style="margin-top:20px; padding: 15px; background: #e9ecef;">
        <strong>Result:</strong> <?= htmlspecialchars($result) ?>
    </div>
<?php endif; ?>

<p style="margin-top: 20px;"><a href="/tools">&laquo; Back to Tools</a></p>
