<?php
namespace Concrete\Package\ExportPages;

use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $appVersionRequired = '8.5.1';
    protected $pkgHandle = 'export_pages';
    protected $pkgVersion = '0.0.1';
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
