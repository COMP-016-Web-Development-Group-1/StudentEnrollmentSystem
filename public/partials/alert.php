<?php if (isset($_SESSION['message'])): ?>
    <?php
    $type = $_SESSION['message']['type'];
    $text = $_SESSION['message']['text'];

    // Define color classes
    $styles = [
        'success' => 'text-green-800 bg-green-50 border border-green-300',
        'error' => 'text-red-800 bg-red-50 border border-red-300',
    ];

    $icons = [
        'success' => 'ti ti-circle-check',
        'error' => 'ti ti-circle-x',
    ];

    $styleClass = $styles[$type] ?? null;
    $iconClass = $icons[$type] ?? null;
    ?>
    <div class="p-4 mb-4 my-3 text-sm <?= $styleClass ?> rounded-lg" role="alert">
        <i class="<?= $iconClass ?>"></i>
        <?= htmlspecialchars($text) ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>