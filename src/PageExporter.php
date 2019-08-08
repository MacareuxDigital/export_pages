<?php
namespace Concrete5cojp\ExportPages;

use Concrete\Core\Attribute\Category\PageCategory;
use Concrete\Core\Attribute\ObjectInterface;
use Concrete\Core\Csv\Export\AbstractExporter;
use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Page\Collection\Collection;
use Concrete\Core\Page\Page;
use Concrete\Core\User\UserInfoRepository;
use League\Csv\Writer;
use Punic\Exception\BadArgumentType;

class PageExporter extends AbstractExporter
{
    /**
     * @var Date
     */
    protected $dateService;

    /**
     * @var UserInfoRepository
     */
    protected $userInfoRepository;

    /**
     * @param Writer $writer
     * @param Date $dateService
     * @param PageCategory $pageCategory
     * @param UserInfoRepository $userInfoRepository
     */
    public function __construct(Writer $writer, Date $dateService, PageCategory $pageCategory, UserInfoRepository $userInfoRepository)
    {
        parent::__construct($writer);

        $this->dateService = $dateService;
        $this->setCategory($pageCategory);
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getStaticHeaders()
    {
        yield t('ID');
        yield t('Type');
        yield t('Name');
        yield t('URL');
        yield t('Date');
        yield t('Last Modified');
        yield t('Author');
    }

    /**
     * @inheritDoc
     */
    protected function getStaticFieldValues(ObjectInterface $page)
    {
        /** @var Page|Collection $page */
        yield $page->getCollectionID();
        yield $page->getPageTypeName();
        yield $page->getCollectionName();
        yield $page->getCollectionLink();
        yield $this->getLocalizedDate($page->getCollectionDatePublic());
        yield $this->getLocalizedDate($page->getCollectionDateLastModified());
        $ui = $this->userInfoRepository->getByID($page->getCollectionUserID());
        yield (is_object($ui)) ? $ui->getUserDisplayName() : t('Deleted User');
    }

    /**
     * Converts a system string date to a (localized) app string date.
     *
     * @param string|null $value E.g. '2018-21-31 23:59:59'
     *
     * @return string|null
     */
    private function getLocalizedDate($value = null)
    {
        if ($value) {
            try {
                $value = $this->dateService
                    ->toDateTime($value, 'app')
                    ->format(Date::DB_FORMAT);
            } catch (BadArgumentType $e) {
                // Do nothing
            }
        }

        return $value;
    }
}