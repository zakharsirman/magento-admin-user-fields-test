<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\User\Model\User;

/**
 * Save handler for additional admin user fields
 */
interface AdditionalFieldSaveHandlerInterface {

    /**
     * @param User $user
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(User $user) : void;

    /**
     * @return string|null
     */
    public function getFieldCode() : ?string;
}
