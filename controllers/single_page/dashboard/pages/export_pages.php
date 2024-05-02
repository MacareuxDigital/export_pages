<?php
namespace Concrete\Package\ExportPages\Controller\SinglePage\Dashboard\Pages;

use Concrete\Core\Csv\WriterFactory;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\PageList;
use Concrete\Core\Site\Service;
use Concrete5cojp\ExportPages\PageExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportPages extends DashboardPageController
{
    public function view()
    {
        /** @var Service $service */
        $service = $this->app->make(Service::class);
        $sites = [];
        foreach ($service->getList() as $site) {
            $sites[$site->getSiteID()] = $site->getSiteName();
        }
        $this->set('sites', $sites);
    }

    public function select_language()
    {
        $siteID = $this->get('site');
        if (!$siteID) {
            $this->error->add(t('Please select a site.'));
            $this->view();
            return;
        }

        /** @var Service $service */
        $service = $this->app->make(Service::class);
        /** @var \Concrete\Core\Entity\Site\Site $site */
        $site = $service->getByID($siteID);
        if (!$site) {
            $this->error->add(t('Invalid site.'));
            $this->view();
            return;
        }

        $trees = [];
        $locales = $site->getLocales();
        foreach ($locales as $locale) {
            $trees[$locale->getSiteTreeID()] = $locale->getLanguageText();
        }
        $this->set('trees', $trees);
        $this->set('siteID', $siteID);
    }

    public function csv_export()
    {
        if (!$this->token->validate('export_pages')) {
            $this->error->add($this->token->getErrorMessage());
        }

        if ($this->error->has()) {
            return $this->view();
        }

        return StreamedResponse::create(
            function () {
                $writer = $this->getWriter();
                $writer->setUnloadDoctrineEveryTick(50);
                $writer->insertHeaders();
                $writer->insertList($this->getList());
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=pages.csv',
            ]
        );
    }

    /**
     * @return PageExporter
     */
    private function getWriter()
    {
        return $this->app->make(
            PageExporter::class, [
                'writer' => $this->app->make(WriterFactory::class)
                    ->createFromPath('php://output', 'w'),
            ]
        );
    }

    /**
     * @return PageList
     */
    private function getList()
    {
        $list = new PageList();
        $list->setSiteTreeToAll();
        $list->ignorePermissions();
        $siteID = (int) $this->request->request->get('site');
        $treeID = (int) $this->request->request->get('tree');
        if ($siteID && $treeID) {
            /** @var Service $service */
            $service = $this->app->make(Service::class);
            $site = $service->getByID($siteID);
            if ($site) {
                foreach ($site->getLocales() as $locale) {
                    if ($locale->getSiteTreeID() === $treeID) {
                        $list->getQueryObject()
                            ->andWhere($list->getQueryObject()->expr()->eq('siteTreeID', ':siteTreeID'))
                            ->setParameter('siteTreeID', $treeID)
                        ;
                    }
                }
            }
        }

        return $list;
    }
}
