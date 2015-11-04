<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\EntityPrivilege;
use TYPO3\Media\Domain\Model\Tag;
use Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine\TagConditionGenerator;

/**
 * Privilege for restricting reading of Tags
 */
class ReadTagPrivilege extends EntityPrivilege
{

    /**
     * @param string $entityType
     * @return boolean
     */
    public function matchesEntityType($entityType)
    {
        return $entityType === Tag::class;
    }

    /**
     * @return TagConditionGenerator
     */
    protected function getConditionGenerator()
    {
        return new TagConditionGenerator();
    }
}