<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Model;

use IdeaInYou\AdminUserAdditionalFields\Api\AdditionalFieldSaveHandlerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\User\Model\User;

/**
 * Default save handler for additional admin user fields
 */
class DefaultSaveHandler implements AdditionalFieldSaveHandlerInterface
{
    private ?string $fieldCode;
    private RequestInterface $request;

    /**
     * @param RequestInterface $request
     * @param string|null $fieldCode
     */
    public function __construct(RequestInterface $request, ?string $fieldCode)
    {
        $this->fieldCode = $fieldCode;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        $value = $this->request->getParam($this->getFieldCode());

        if ($value) {
            $user->setData($this->getFieldCode(), $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldCode(): ?string
    {
        return $this->fieldCode;
    }
}
