<?php
$page_title = 'Yorumlar';
include 'includes/header.php';

// Display session messages
if (isset($_SESSION['success_message'])) {
    echo alert($_SESSION['success_message'], 'success');
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo alert($_SESSION['error_message'], 'danger');
    unset($_SESSION['error_message']);
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM testimonials WHERE id=" . (int)$_GET['id']);
    $_SESSION['success_message'] = 'Yorum silindi!';
    redirect('testimonials.php');
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = sanitize($_POST['name']);
    $location = sanitize($_POST['location']);
    $rating = (int)$_POST['rating'];
    $comment = $conn->real_escape_string($_POST['comment']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0) {
        $conn->query("UPDATE testimonials SET name='$name', location='$location', rating=$rating, comment='$comment', display_order=$display_order, is_active=$is_active WHERE id=$id");
        $_SESSION['success_message'] = 'Yorum güncellendi!';
    } else {
        $conn->query("INSERT INTO testimonials (name, location, rating, comment, display_order, is_active) VALUES('$name', '$location', $rating, '$comment', $display_order, $is_active)");
        $_SESSION['success_message'] = 'Yorum eklendi!';
    }
    redirect('testimonials.php');
}

// Get edit data
$edit_data = isset($_GET['edit']) ? $conn->query("SELECT * FROM testimonials WHERE id=" . (int)$_GET['edit'])->fetch_assoc() : null;
?>

<div class="row">
    <div class="col-md-5">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo $edit_data ? 'Yorum Düzenle' : 'Yeni Yorum'; ?>
            </h5>
            <form method="POST">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">İsim *</label>
                    <input name="name" class="form-control" value="<?php echo $edit_data['name'] ?? ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konum</label>
                    <input name="location" class="form-control" value="<?php echo $edit_data['location'] ?? ''; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Puan</label>
                    <select name="rating" class="form-control">
                        <option value="5" <?php echo ($edit_data['rating'] ?? 5) == 5 ? 'selected' : ''; ?>>5 - Mükemmel</option>
                        <option value="4" <?php echo ($edit_data['rating'] ?? 5) == 4 ? 'selected' : ''; ?>>4 - Çok İyi</option>
                        <option value="3" <?php echo ($edit_data['rating'] ?? 5) == 3 ? 'selected' : ''; ?>>3 - İyi</option>
                        <option value="2" <?php echo ($edit_data['rating'] ?? 5) == 2 ? 'selected' : ''; ?>>2 - Orta</option>
                        <option value="1" <?php echo ($edit_data['rating'] ?? 5) == 1 ? 'selected' : ''; ?>>1 - Zayıf</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Yorum *</label>
                    <textarea name="comment" class="form-control" rows="4" required><?php echo $edit_data['comment'] ?? ''; ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sıra</label>
                    <input type="number" name="display_order" class="form-control" value="<?php echo $edit_data['display_order'] ?? 0; ?>">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                           <?php echo (!$edit_data || $edit_data['is_active']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="fas fa-save me-2"></i><?php echo $edit_data ? 'Güncelle' : 'Ekle'; ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="testimonials.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>İptal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-comments me-2"></i>Yorumlar</h5>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>İsim</th>
                        <th>Konum</th>
                        <th>Puan</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM testimonials ORDER BY display_order");
                    while ($testimonial = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                        <td><?php echo htmlspecialchars($testimonial['location']); ?></td>
                        <td>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?php echo $i <= $testimonial['rating'] ? '' : '-o'; ?>" style="color: #ffd700;"></i>
                            <?php endfor; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $testimonial['is_active'] ? 'success' : 'secondary'; ?>">
                                <?php echo $testimonial['is_active'] ? 'Aktif' : 'Pasif'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-danger delete-confirm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
