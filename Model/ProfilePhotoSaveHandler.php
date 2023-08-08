<?php

declare(strict_types=1);

namespace IdeaInYou\AdminUserAdditionalFields\Model;

use IdeaInYou\AdminUserAdditionalFields\Api\AdditionalFieldSaveHandlerInterface;
use IdeaInYou\AdminUserAdditionalFields\Api\Data\AdditionalFieldInterface;
use Laminas\Http\Request;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\User\Model\User;
use Magento\Framework\App\RequestInterface;

/**
 * Save handler for the profile photo
 */
class ProfilePhotoSaveHandler implements AdditionalFieldSaveHandlerInterface
{
    protected ImageUploader $imageUploader;

    private ?string $fieldCode;

    private RequestInterface $request;

    /**
     * @param ImageUploader $imageUploader
     * @param RequestInterface $request
     * @param string|null $fieldCode
     */
    public function __construct(ImageUploader $imageUploader, RequestInterface $request, ?string $fieldCode)
    {
        $this->imageUploader = $imageUploader;
        $this->request = $request;
        $this->fieldCode = $fieldCode;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        if ($this->request instanceof Request
            && $this->request->getFiles($this->getFieldCode())
            && $this->request->getFiles($this->getFieldCode())['name']
        ) {
            try {
                $result = $this->imageUploader->saveFileToTmpDir($this->getFieldCode());
                $user->setData($this->getFieldCode(), $result['url']);
                return;
            } catch (LocalizedException $exception) {
                throw new CouldNotSaveException(__($exception->getMessage()));
            }
        }

        if ($this->checkImageDelete()) {
            $user->setData($this->getFieldCode());
            return;
        }

        if ($this->checkExistingImage()) {
            $user->setData($this->getFieldCode(), $this->request->getParam($this->getFieldCode())['value']);
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldCode(): ?string
    {
        return $this->fieldCode ?? AdditionalFieldInterface::PROFILE_PHOTO_FIELD;
    }

    /**
     * @return bool
     */
    private function checkImageDelete(): bool
    {
        return $this->request->getParam($this->getFieldCode()) !== null &&
            isset($this->request->getParam($this->getFieldCode())["delete"]);
    }

    /**
     * Check if we save the same image as before to prevent error during db save
     *
     * @return bool
     */
    private function checkExistingImage(): bool
    {
        return $this->request->getParam($this->getFieldCode()) !== null &&
            is_array($this->request->getParam($this->getFieldCode()));
    }
}
