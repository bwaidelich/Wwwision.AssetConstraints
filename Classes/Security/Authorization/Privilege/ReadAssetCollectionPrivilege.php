<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege;

use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\EntityPrivilege;
use Neos\Media\Domain\Model\AssetCollection;
use Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine\AssetCollectionConditionGenerator;

/**
 * Privilege for restricting reading of AssetCollections
 */
class ReadAssetCollectionPrivilege extends EntityPrivilege
{
    /**
     * @param string $entityType
     * @return boolean
     */
    public function matchesEntityType($entityType)
    {
        return $entityType === AssetCollection::class;
    }

    /**
     * @return AssetCollectionConditionGenerator
     */
    protected function getConditionGenerator()
    {
        return new AssetCollectionConditionGenerator();
    }
}
