<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Filter\SQLFilter as DoctrineSqlFilter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\SqlGeneratorInterface;

/**
 * Condition generator covering Asset >-< AssetCollection relations (M:M relations are not supported by the Flow
 * PropertyConditionGenerator yet)
 */
class AssetWithoutAssetCollectionConditionGenerator implements SqlGeneratorInterface
{
    /**
     * @Flow\Inject
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @param DoctrineSqlFilter $sqlFilter
     * @param ClassMetadata $targetEntity Metadata object for the target entity to create the constraint for
     * @param string $targetTableAlias The target table alias used in the current query
     * @return string
     */
    public function getSql(DoctrineSqlFilter $sqlFilter, ClassMetadata $targetEntity, $targetTableAlias)
    {
        $sql = $targetTableAlias . '.persistence_object_identifier IN (
            SELECT ' . $targetTableAlias . '_a.persistence_object_identifier
            FROM neos_media_domain_model_asset AS ' . $targetTableAlias . '_a
            LEFT JOIN neos_media_domain_model_assetcollection_assets_join ' . $targetTableAlias . '_acj ON ' . $targetTableAlias . '_a.persistence_object_identifier = ' . $targetTableAlias . '_acj.media_asset
            WHERE ' . $targetTableAlias . '_acj.media_asset IS NULL)';

        return $sql;
    }
}
