<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Plugin;

use IdeaInYou\AdminUserAdditionalFields\Api\AdditionalFieldSaveHandlerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\User\Model\User;

class FieldCodeCheck
{
    /**
     * @param AdditionalFieldSaveHandlerInterface $subject
     * @param callable $proceed
     * @param User $user
     * @return void
     * @throws CouldNotSaveException
     */
    public function aroundSave(AdditionalFieldSaveHandlerInterface $subject, callable $proceed, User $user): void
    {
        if (!$subject->getFieldCode()) {
          throw new CouldNotSaveException(__('Field Code is not set'));
        }

        $proceed($user);
    }
}
