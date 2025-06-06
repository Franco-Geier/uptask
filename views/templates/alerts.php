<?php
    foreach ($alerts as $key => $alert):
        foreach($alert as $message):
?>

    <div class="alert <?php echo $key; ?>">
        <p><?php echo $message; ?></p>
    </div>

<?php
        endforeach;
    endforeach;
?>