<?php
// index.php
// Web App CRUD Resit - PHP + Bootstrap (Mobile Friendly)

// Connect to Database
require_once 'connection_work.php';

if (!$conn) {
	throw new Exception("Connection to Database not exist.");
}


// --- Update Receipt ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
  $stmt = $conn->prepare("UPDATE TBL_Receipt SET Header=?, Body=?, Create_datetime=?, Address=?, Item=?, Quantity=?, Price=?, Tax=? WHERE ID=?");
  $stmt->bind_param("sssssiidi",
    $_POST['Header'],
    $_POST['Body'],
    $_POST['Create_datetime'],
    $_POST['Address'],
    $_POST['Item'],
    $_POST['Quantity'],
    $_POST['Price'],
    $_POST['Tax'],
    $_POST['edit_id']
  );
  $stmt->execute();
  $stmt->close();
  echo json_encode(["status" => "success"]); exit;
}

// --- Delete Receipt ---
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $conn->query("DELETE FROM TBL_Receipt WHERE ID=$id");
  echo json_encode(["status" => "deleted"]); exit;
}

// --- Get all Receipt ---
$result = $conn->query("SELECT * FROM TBL_Receipt ORDER BY ID DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt From WhatsApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /*@media (max-width: 768px) {
      .modal-dialog {
        max-width: 100% !important;
        margin: 0;
        height: 100%;
      }
      .modal-content {
        height: 100vh;
        border-radius: 0;
      }
    }*/
	@media (max-width: 576px) {
	  .modal-dialog {
		margin: 0;
		max-width: 100%;
		height: 100%;
	  }

	  .modal-content {
		height: 100vh;
		border-radius: 0;
	  }

	  .modal-body {
		overflow-y: auto;
		padding: 1rem;
	  }
	}
	  
	.btn-action {
	  margin: 4px;
	  padding: 6px 12px;
	}
	.btn-container {
	  display: flex;
	  justify-content: center;
	  flex-wrap: wrap;
	}  
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4">Senarai Resit</h2>
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#resitModal">Tambah Resit</button>
  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="resitTable">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Header</th><th>Item</th><th>Qty</th><th>Harga</th><th>Tax</th><th>Tarikh</th><th>Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr data-id="<?= $row['ID'] ?>">
            <td><?= $row['ID'] ?></td>
            <td><?= htmlspecialchars($row['Header']) ?></td>
            <td><?= htmlspecialchars($row['Item']) ?></td>
            <td><?= $row['Quantity'] ?></td>
            <td>RM<?= number_format($row['Price'], 2) ?></td>
            <td>RM<?= number_format($row['Tax'], 2) ?></td>
            <td><?= $row['Create_datetime'] ?></td>
            <td>
			<div class="btn-container">
              <button class="btn btn-sm btn-warning edit-btn btn-action" data-id="<?= $row['ID'] ?>">Edit</button>
              <button class="btn btn-sm btn-danger delete-btn btn-action" data-id="<?= $row['ID'] ?>">Padam</button>
			</div>	
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Berjaya disimpan!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
  <div id="toastDelete" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Resit dipadam!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Modal Add Receipt-->
<div class="modal fade" id="resitModal" tabindex="-1" aria-labelledby="resitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="resitModalLabel">Tambah Resit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addForm">
          <input type="hidden" name="add" value="1">
          <?php include 'form_fields.php'; ?>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> 

<!-- Modal Edit Receipt--> 
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Resit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" id="editForm">
          <input type="hidden" name="edit_id" id="edit_id">
          <?php include 'form_fields.php'; ?>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Kemaskini</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
	


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-id');
      fetch(`get_receipt.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
          const form = document.querySelector('#editModal form');
          form.querySelector('#edit_id').value = data.ID;
          form.querySelector('input[name=Header]').value = data.Header;
          form.querySelector('input[name=Item]').value = data.Item;
          form.querySelector('textarea[name=Body]').value = data.Body;
          form.querySelector('input[name=Create_datetime]').value = data.Create_datetime;
          form.querySelector('input[name=Address]').value = data.Address;
          form.querySelector('input[name=Quantity]').value = data.Quantity;
          form.querySelector('input[name=Price]').value = data.Price;
          form.querySelector('input[name=Tax]').value = data.Tax;
          const modal = new bootstrap.Modal(document.getElementById('editModal'));
          modal.show();
        });
    });
  });

  document.getElementById('addForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch('submit_add.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById('resitModal')).hide();
        const toast = new bootstrap.Toast(document.getElementById('toastSuccess'));
        toast.show();
        setTimeout(() => location.reload(), 1200);
      } else {
        alert('Gagal simpan: ' + data.message);
      }
    });
  });

  document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch('', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        const toast = new bootstrap.Toast(document.getElementById('toastSuccess'));
        toast.show();
        setTimeout(() => location.reload(), 1200);
      } else {
        alert('Gagal kemaskini');
      }
    });
  });

  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!confirm('Padam resit ini?')) return;
      const id = btn.getAttribute('data-id');
      fetch(`?delete=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'deleted') {
            const row = document.querySelector(`tr[data-id='${id}']`);
            if (row) row.remove();
            const toast = new bootstrap.Toast(document.getElementById('toastDelete'));
            toast.show();
          } else {
            alert('Gagal padam');
          }
        });
    });
  });
</script>
</body>
</html>
