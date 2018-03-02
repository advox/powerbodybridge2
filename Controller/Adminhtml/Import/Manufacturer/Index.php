<?php
declare(strict_types=1);

namespace Powerbody\Bridge\Controller\Adminhtml\Import\Manufacturer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Powerbody\Bridge\Service\Form\FormDataImporterInterface;
use Psr\Log\LoggerInterface;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var FormDataImporterInterface
     */
    private $manufacturerDataImporter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        FormDataImporterInterface $manufacturerDataImporter,
        LoggerInterface $logger
    ) {
        $this->manufacturerDataImporter = $manufacturerDataImporter;
        $this->pageFactory = $pageFactory;
        $this->logger = $logger;
        return parent::__construct($context);
    }

    public function execute() : Page
    {
        try {
            $this->manufacturerDataImporter->importFormData();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage(), [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'previous' => $e->getPrevious(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->messageManager->addErrorMessage(__('Something went wrong while trying to synchronize manufacturers, data may be inconsistent.'));
        }

        return $this->pageFactory->create();
    }
}
