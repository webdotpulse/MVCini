<h2><?= htmlspecialchars($title) ?></h2>

<p>Estimate the required battery capacity based on your power needs (assuming 20% system loss).</p>

<form method="POST" action="/tools/battery_sizer">
    <div class="form-group">
        <label>Total Power Load (Watts)</label>
        <input type="number" step="any" name="power" class="form-control" required value="<?= htmlspecialchars($_POST['power'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Usage Time (Hours)</label>
        <input type="number" step="any" name="hours" class="form-control" required value="<?= htmlspecialchars($_POST['hours'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Battery System Voltage (Volts)</label>
        <select name="voltage" class="form-control">
            <option value="12">12V</option>
            <option value="24">24V</option>
            <option value="48">48V</option>
        </select>
    </div>

    <button type="submit" class="btn">Calculate Required Capacity</button>
</form>

<?php if ($result !== ''): ?>
    <div style="margin-top:20px; padding: 15px; background: #e9ecef;">
        <strong>Recommended Minimum Capacity:</strong> <?= htmlspecialchars($result) ?>
    </div>
<?php endif; ?>

<p style="margin-top: 20px;"><a href="/tools">&laquo; Back to Tools</a></p>
