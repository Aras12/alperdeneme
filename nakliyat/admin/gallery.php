<?php
$page_title = 'Galeri Yönetimi';
include 'includes/header.php';

// Handle delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM gallery WHERE id = " . (int)$_GET['id']);
    echo alert('Görsel silindi!', 'success');
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $alt_text = sanitize($_POST['alt_text']);
    $description = sanitize($_POST['description']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle image upload
    $image = sanitize($_POST['image_url']);
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadImage($_FILES['image_file'], 'gallery');
        if ($uploaded) {
            $image = $uploaded;
        }
    }

    if ($id > 0) {
        $sql = "UPDATE gallery SET image='$image', alt_text='$alt_text', description='$description',
                display_order=$display_order, is_active=$is_active WHERE id=$id";
    } else {
        $sql = "INSERT INTO gallery (image, alt_text, description, display_order, is_active)
                VALUES ('$image', '$alt_text', '$description', $display_order, $is_active)";
    }

    if ($conn->query($sql)) {
        echo alert($id > 0 ? 'Görsel güncellendi!' : 'Görsel eklendi!', 'success');
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = $conn->query("SELECT * FROM gallery WHERE id = " . (int)$_GET['edit'])->fetch_assoc();
}

// List or edit form
if ($edit_data || isset($_GET['new'])):
?>

<div class="content-card">
    <h5 class="mb-4">
        <i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i>
        <?php echo $edit_data ? 'Görsel Düzenle' : 'Yeni Görsel Ekle'; ?>
    </h5>
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Görsel *</label>
            <?php if ($edit_data && $edit_data['image']): ?>
            <div class="mb-2">
                <img src="<?php echo $edit_data['image']; ?>" alt="Mevcut Görsel" style="max-width: 300px; max-height: 200px; object-fit: cover;" class="img-thumbnail">
            </div>
            <?php endif; ?>
            <input type="file" name="image_file" class="form-control mb-2" accept="image/*">
            <input type="url" name="image_url" class="form-control" placeholder="veya harici URL girin"
                   value="<?php echo $edit_data['image'] ?? ''; ?>">
            <small class="text-muted">Dosya yükleyin veya harici bir URL girin</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Alt Etiketi (Alt Text) *</label>
            <input type="text" name="alt_text" class="form-control" required
                   value="<?php echo $edit_data['alt_text'] ?? ''; ?>"
                   placeholder="Örn: Adana oto çekici hizmeti">
            <small class="text-muted">SEO için önemli - görseli tanımlayın</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $edit_data['description'] ?? ''; ?></textarea>
            <small class="text-muted">Görselle ilgili detaylı açıklama (isteğe bağlı)</small>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Sıra</label>
                    <input type="number" name="display_order" class="form-control"
                           value="<?php echo $edit_data['display_order'] ?? 0; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 form-check mt-4">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                           <?php echo (!$edit_data || $edit_data['is_active']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i><?php echo $edit_data ? 'Güncelle' : 'Ekle'; ?>
            </button>
            <a href="gallery.php" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>İptal
            </a>
        </div>
    </form>
</div>

<?php else:
$gallery = $conn->query("SELECT * FROM gallery ORDER BY display_order ASC");
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Galeri Görselleri</h5>
        <a href="?new=1" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Yeni Görsel
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 80px;">Sıra</th>
                    <th style="width: 120px;">Görsel</th>
                    <th>Alt Etiketi</th>
                    <th>Açıklama</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 120px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $gallery->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $item['display_order']; ?></td>
                        <td>
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['alt_text']); ?>"
                                 style="width: 100px; height: 60px; object-fit: cover;" class="img-thumbnail">
                        </td>
                        <td><?php echo htmlspecialchars($item['alt_text']); ?></td>
                        <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)); ?><?php echo strlen($item['description']) > 50 ? '...' : ''; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $item['is_active'] ? 'success' : 'secondary'; ?>">
                                <?php echo $item['is_active'] ? 'Aktif' : 'Pasif'; ?>
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $item['id']; ?>"
                               class="btn btn-sm btn-danger delete-confirm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
