<?php
defined('C5_EXECUTE') or die('Access Denied.');
/** @var \Concrete\Package\ExportPages\Controller\SinglePage\Dashboard\Pages\ExportPages $controller */
/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var array $sites */
$sites = isset($sites) ? $sites : [];
/** @var array $trees */
$trees = isset($trees) ? $trees : [];
$siteID = isset($siteID) ? $siteID : null;

if ($sites) {
    ?>
    <form role="form" method="post" action="<?php echo $controller->action('select_language'); ?>">
        <?php $token->output('export_pages'); ?>
        <p><?php echo t('Export pages to CSV'); ?></p>
        <div class="form-group">
            <?php echo $form->label('site', t('Select Site to Export')); ?>
            <?php echo $form->select('site', $sites); ?>
        </div>
        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button type="submit" class="btn btn-primary float-end">
                    <?php echo t('Select Site'); ?>
                </button>
            </div>
        </div>
    </form>
    <?php
}
if ($trees) {
    ?>
    <form role="form" method="post" action="<?php echo $controller->action('csv_export'); ?>">
        <?php $token->output('export_pages'); ?>
        <?php echo $form->hidden('site', $siteID); ?>
        <div class="form-group">
            <?php echo $form->label('tree', t('Select Language to Export')); ?>
            <?php echo $form->select('tree', $trees); ?>
        </div>
        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button type="submit" class="btn btn-primary float-end">
                    <?php echo t('Export Pages'); ?>
                </button>
            </div>
        </div>
    </form>
    <?php
}
