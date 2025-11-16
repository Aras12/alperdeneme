<?php
$page_title = 'Mesajlar';
include 'includes/header.php';

// Mark as read
if (isset($_GET['action']) && $_GET['action'] == 'read' && isset($_GET['id'])) {
    $conn->query("UPDATE messages SET is_read = 1 WHERE id = " . (int)$_GET['id']);
}

// Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM messages WHERE id = " . (int)$_GET['id']);
    echo alert('Mesaj silindi!', 'success');
}

// View single message
if (isset($_GET['view'])):
    $msg = $conn->query("SELECT * FROM messages WHERE id = " . (int)$_GET['view'])->fetch_assoc();
    $conn->query("UPDATE messages SET is_read = 1 WHERE id = " . (int)$_GET['view']);
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Mesaj Detayı</h5>
        <a href="messages.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Geri
        </a>
    </div>

    <table class="table">
        <tr>
            <th width="150">Gönderen:</th>
            <td><?php echo htmlspecialchars($msg['name']); ?></td>
        </tr>
        <tr>
            <th>Telefon:</th>
            <td><a href="tel:<?php echo $msg['phone']; ?>"><?php echo htmlspecialchars($msg['phone']); ?></a></td>
        </tr>
        <tr>
            <th>E-posta:</th>
            <td><?php echo htmlspecialchars($msg['email']); ?></td>
        </tr>
        <tr>
            <th>Konu:</th>
            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
        </tr>
        <tr>
            <th>Tarih:</th>
            <td><?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></td>
        </tr>
    </table>

    <div class="alert alert-light">
        <strong>Mesaj:</strong>
        <p class="mb-0 mt-2"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
    </div>

    <div class="d-flex gap-2">
        <a href="tel:<?php echo $msg['phone']; ?>" class="btn btn-primary">
            <i class="fas fa-phone me-2"></i>Ara
        </a>
        <a href="https://wa.me/9<?php echo preg_replace('/[^0-9]/', '', $msg['phone']); ?>" class="btn btn-success" target="_blank">
            <i class="fab fa-whatsapp me-2"></i>WhatsApp
        </a>
        <a href="?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-danger delete-confirm">
            <i class="fas fa-trash me-2"></i>Sil
        </a>
    </div>
</div>

<?php else:
$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<div class="content-card">
    <h5 class="mb-4"><i class="fas fa-inbox me-2"></i>Gelen Mesajlar</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>İsim</th>
                    <th>Telefon</th>
                    <th>Konu</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <tr class="<?php echo !$msg['is_read'] ? 'table-primary' : ''; ?>">
                        <td><?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td><?php echo htmlspecialchars($msg['phone']); ?></td>
                        <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                        <td>
                            <?php if ($msg['is_read']): ?>
                                <span class="badge bg-secondary">Okundu</span>
                            <?php else: ?>
                                <span class="badge bg-success">Yeni</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <a href="?view=<?php echo $msg['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $msg['id']; ?>"
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
