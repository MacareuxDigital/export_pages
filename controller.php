<?php
namespace Concrete\Package\ExportPages;

use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $appVersionRequired = '8.5.0';
    protected $pkgHandle = 'export_pages';
    protected $pkgVersion = '1.0.0';
    protected $pkgAutoloaderRegistries = [
        'src' => '\Concrete5cojp\ExportPages',
    ];

    public function getPackageName()
    {
        return t('Export Pages');
    }

    public function getPackageDescription()
    {
        return t('Make it enables to export pages as csv files');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installContentFile('config/singlepages.xml');

        return $pkg;
    }
}
