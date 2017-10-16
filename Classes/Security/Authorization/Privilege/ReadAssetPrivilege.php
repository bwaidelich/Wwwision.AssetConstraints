<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege;

use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\EntityPrivilege;
use Neos\Media\Domain\Model\Asset;
use Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine\AssetConditionGenerator;

/**
 * Privilege for restricting reading of Assets
 */
class ReadAssetPrivilege extends EntityPrivilege
{
    /**
     * @param string $entityType
     * @return boolean
     */
    public function matchesEntityType($entityType)
    {
        return $entityType === Asset::class;
    }

    /**
     * @return AssetConditionGenerator
     */
    protected function getConditionGenerator()
    {
        return new AssetConditionGenerator();
    }
}
