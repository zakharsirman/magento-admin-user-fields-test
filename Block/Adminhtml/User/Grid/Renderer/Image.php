<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Block\Adminhtml\User\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Image renderer for columns in the admin user grid
 */
class Image extends AbstractRenderer
{
    /**
     * @param DataObject $row
     * @return string
     * @throws NoSuchEntityException
     */
    public function render(DataObject $row): string
    {
        $value = $row->getData($this->getColumn()->getIndex());
        if ($value) {
            return '<img src="' . $value . '" width="150" height="100" alt="' . $value . '">';
        }

        $placeholderImageUrl = $this->getViewFileUrl('Magento_Catalog::images/product/placeholder/image.jpg');
        return '<img src="' . $placeholderImageUrl . '" width="150" height="100" alt="Placeholder Image">';
    }
}
