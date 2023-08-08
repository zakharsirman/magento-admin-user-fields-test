<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Plugin\Model\User;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\User\Model\ResourceModel\User as UserResource;
use Magento\User\Model\User as UserModel;
use IdeaInYou\AdminUserAdditionalFields\Api\AdditionalFieldSaveHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Save additional fields data to the admin user object
 */
class UserAdditionalFieldsSave
{
    private AdditionalFieldSaveHandlerInterface $saveHandler;

    private LoggerInterface $logger;

    /**
     * @param AdditionalFieldSaveHandlerInterface $saveHandler
     * @param LoggerInterface $logger
     */
    public function __construct(AdditionalFieldSaveHandlerInterface $saveHandler, LoggerInterface $logger)
    {
        $this->saveHandler = $saveHandler;
        $this->logger = $logger;
    }

    /**
     * @param UserResource $subject
     * @param UserModel $user
     * @return array
     */
    public function beforeSave(UserResource $subject, UserModel $user): array
    {
        try {
            $this->saveHandler->save($user);
        } catch (CouldNotSaveException $exception) {
            $this->logger->error(
                __("Error during additional admin user fields save ") . $exception->getMessage()
            );
        }
        return [$user];
    }
}
