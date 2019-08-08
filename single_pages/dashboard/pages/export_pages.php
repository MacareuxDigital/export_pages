<?php
defined('C5_EXECUTE') or die('Access Denied.');
?>
<form role="form" method="post" action="<?php echo $controller->action('csv_export'); ?>">
    <?php $token->output('export_pages'); ?>
    <p><?php echo t('Export pages to CSV'); ?></p>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button type="submit" class="btn btn-primary pull-right">
                <?php echo t('Export Pages to CSV'); ?>
            </button>
        </div>
    </div>
</form>
