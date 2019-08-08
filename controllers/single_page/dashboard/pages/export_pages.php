<?php
namespace Concrete\Package\ExportPages\Controller\SinglePage\Dashboard\Pages;

use Concrete\Core\Csv\WriterFactory;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\PageList;
use Concrete5cojp\ExportPages\PageExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportPages extends DashboardPageController
{
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
        $list->ignorePermissions();

        return $list;
    }
}
