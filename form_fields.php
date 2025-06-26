<!-- form_fields.php -->
<div class="mb-3">
  <label class="form-label">Header</label>
  <input type="text" name="Header" class="form-control" required value="<?= isset($editData) ? htmlspecialchars($editData['Header']) : '' ?>">
</div>
<div class="mb-3">
  <label class="form-label">Item</label>
  <input type="text" name="Item" class="form-control" required value="<?= isset($editData) ? htmlspecialchars($editData['Item']) : '' ?>">
</div>
<div class="mb-3">
  <label class="form-label">Body</label>
  <textarea name="Body" class="form-control" rows="2"><?= isset($editData) ? htmlspecialchars($editData['Body']) : '' ?></textarea>
</div>
<div class="mb-3">
  <label class="form-label">Tarikh</label>
  <input type="date" name="Create_datetime" class="form-control" value="<?= isset($editData) ? $editData['Create_datetime'] : date('Y-m-d') ?>">
</div>
<div class="mb-3">
  <label class="form-label">Alamat</label>
  <input type="text" name="Address" class="form-control" value="<?= isset($editData) ? htmlspecialchars($editData['Address']) : '' ?>">
</div>
<div class="mb-3">
  <label class="form-label">Quantity</label>
  <input type="number" name="Quantity" class="form-control" required value="<?= isset($editData) ? $editData['Quantity'] : '' ?>">
</div>
<div class="mb-3">
  <label class="form-label">Price (RM)</label>
  <input type="number" step="0.01" name="Price" class="form-control" required value="<?= isset($editData) ? $editData['Price'] : '' ?>">
</div>
<div class="mb-3">
  <label class="form-label">Tax (RM)</label>
  <input type="number" step="0.01" name="Tax" class="form-control" required value="<?= isset($editData) ? $editData['Tax'] : '' ?>">
</div>