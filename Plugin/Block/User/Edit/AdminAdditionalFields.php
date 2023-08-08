<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Plugin\Block\User\Edit;

use IdeaInYou\AdminUserAdditionalFields\Api\Data\AdditionalFieldInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Data\Form;
use Magento\Framework\Registry;
use Magento\User\Block\User\Edit\Tab\Main;
use Magento\User\Model\User;
use Magento\Backend\Block\Widget\Form as FormBlock;
use Magento\User\Model\UserFactory;

/**
 * Adding additional fields to admin user edit form
 */
class AdminAdditionalFields
{
    protected Registry $_coreRegistry;
    protected Session $_authSession;
    protected UserFactory $_userFactory;

    /**
     * @param Registry $coreRegistry
     * @param Session $authSession
     * @param UserFactory $userFactory
     */
    public function __construct(Registry $coreRegistry, Session $authSession, UserFactory $userFactory)
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_authSession = $authSession;
        $this->_userFactory = $userFactory;
    }

    /**
     * @param FormBlock $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetFormHtml(FormBlock $subject, callable $proceed): string
    {
        $form = $subject->getForm();
        if (is_object($form)) {
            $fieldset = $form->addFieldset('additional_fields', ['legend' => __('Additional Fields')]);
            $fieldset->addField(
                AdditionalFieldInterface::JOB_DESCRIPTION_FIELD,
                'textarea',
                [
                    'name' => AdditionalFieldInterface::JOB_DESCRIPTION_FIELD,
                    'label' => __('Job Description'),
                    'id' => AdditionalFieldInterface::JOB_DESCRIPTION_FIELD,
                    'title' => __('Job Description'),
                    'required' => false
                ]
            );

            $fieldset->addField(
                AdditionalFieldInterface::PROFILE_PHOTO_FIELD,
                'image',
                [
                    'name' => AdditionalFieldInterface::PROFILE_PHOTO_FIELD,
                    'label' => __('Profile Photo'),
                    'id' => AdditionalFieldInterface::PROFILE_PHOTO_FIELD,
                    'title' => __('Profile Photo'),
                    'required' => false,
                    'note' => __('Allowed image types: jpg, jpeg, png')
                ]
            );

            $fieldset->addField(
                AdditionalFieldInterface::PHONE_NUMBER_FIELD,
                'text',
                [
                    'name' => AdditionalFieldInterface::PHONE_NUMBER_FIELD,
                    'label' => __('Phone Number'),
                    'id' => AdditionalFieldInterface::PHONE_NUMBER_FIELD,
                    'title' => __('Phone Number'),
                    'class' => 'validate-phoneStrict',
                    'required' => false
                ]
            );

            $form = $this->setFormData($form);
            $subject->setForm($form);
        }

        return $proceed();
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function setFormData(Form $form): Form
    {
        $user = $this->getUser();
        $data = $user->getData();

        if (isset($data['user_id'])) {
            unset($data[Main::CURRENT_USER_PASSWORD_FIELD]);
            $form->setValues($data);
        }

        return $form;
    }

    /**
     * @return User
     */
    protected function getUser(): User
    {
        $userId = $this->_authSession->getUser()->getId();
        $user = $this->_userFactory->create()->load($userId);
        $user->unsetData('password');

        return $user;
    }
}
