<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Plugin\Block\Widget;

use Magento\Framework\Data\Form;
use Magento\Backend\Block\Widget\Form as FormBlock;

/**
 * Setting encrypt type to admin user forms to allow image upload
 */
class SetFormEncrypt
{
    /**
     * @param FormBlock $subject
     * @param Form $result
     * @return Form
     */
    public function afterGetForm(FormBlock $subject, Form $result): Form
    {
        $result->setData('enctype', 'multipart/form-data');
        return $result;
    }
}
