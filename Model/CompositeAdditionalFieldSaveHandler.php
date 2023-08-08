<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Model;

use IdeaInYou\AdminUserAdditionalFields\Api\AdditionalFieldSaveHandlerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\User\Model\User;

class CompositeAdditionalFieldSaveHandler implements AdditionalFieldSaveHandlerInterface {

    public const COMPOSITE_FIELD_CODE = 'default';

    /**
     * @var AdditionalFieldSaveHandlerInterface[]
     */
    private array $saveHandlers;

    /**
     * @param array $saveHandlers
     */
    public function __construct(array $saveHandlers)
    {
        $this->saveHandlers = $saveHandlers;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        try {
            foreach ($this->saveHandlers as $saveHandler) {
                $saveHandler->save($user);
            }
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldCode(): ?string
    {
        return self::COMPOSITE_FIELD_CODE;
    }
}
