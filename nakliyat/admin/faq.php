<?php
$page_title = 'SSS Yönetimi';
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
    $conn->query("DELETE FROM faqs WHERE id=" . (int)$_GET['id']);
    $_SESSION['success_message'] = 'SSS silindi!';
    redirect('faq.php');
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $question = sanitize($_POST['question']);
    $answer = $conn->real_escape_string($_POST['answer']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0) {
        $conn->query("UPDATE faqs SET question='$question', answer='$answer', display_order=$display_order, is_active=$is_active WHERE id=$id");
        $_SESSION['success_message'] = 'SSS güncellendi!';
    } else {
        $conn->query("INSERT INTO faqs (question, answer, display_order, is_active) VALUES('$question', '$answer', $display_order, $is_active)");
        $_SESSION['success_message'] = 'SSS eklendi!';
    }
    redirect('faq.php');
}

// Get edit data
$edit_data = isset($_GET['edit']) ? $conn->query("SELECT * FROM faqs WHERE id=" . (int)$_GET['edit'])->fetch_assoc() : null;
?>

<div class="row">
    <div class="col-md-5">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo $edit_data ? 'SSS Düzenle' : 'Yeni SSS'; ?>
            </h5>
            <form method="POST">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Soru *</label>
                    <input name="question" class="form-control" value="<?php echo $edit_data['question'] ?? ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cevap *</label>
                    <textarea name="answer" class="form-control" rows="5" required><?php echo $edit_data['answer'] ?? ''; ?></textarea>
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
                        <a href="faq.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>İptal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-list me-2"></i>SSS Listesi</h5>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Soru</th>
                        <th>Sıra</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM faqs ORDER BY display_order");
                    while ($faq = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($faq['question']); ?></td>
                        <td><?php echo $faq['display_order']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $faq['is_active'] ? 'success' : 'secondary'; ?>">
                                <?php echo $faq['is_active'] ? 'Aktif' : 'Pasif'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $faq['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-danger delete-confirm">
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
