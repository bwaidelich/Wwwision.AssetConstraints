<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\ConditionGenerator as EntityConditionGenerator;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\PropertyConditionGenerator;
use TYPO3\Flow\Security\Exception\InvalidPrivilegeException;
use TYPO3\Media\Domain\Model\Asset;

/**
 * A SQL condition generator, supporting special SQL constraints for assets
 */
class AssetConditionGenerator extends EntityConditionGenerator
{

    /**
     * @var string
     */
    protected $entityType = Asset::class;

    /**
     * @param string $entityType
     * @return boolean
     * @throws InvalidPrivilegeException
     */
    public function isType($entityType)
    {
        throw new InvalidPrivilegeException('The isType() operator must not be used in Asset privilege matchers!', 1417083500);
    }

    /**
     * @param string $term
     * @return PropertyConditionGenerator
     */
    public function titleStartsWith($term)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('title');
        return $propertyConditionGenerator->like($term . '%');
    }

    /**
     * @param string $mediaType
     * @return PropertyConditionGenerator
     */
    public function hasMediaType($mediaType)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('resource.mediaType');
        return $propertyConditionGenerator->equals($mediaType);
    }

    /**
     * @param string $tagLabel
     * @return AssetTagConditionGenerator
     */
    public function isTagged($tagLabel)
    {
        return new AssetTagConditionGenerator($tagLabel);
    }

    /**
     * @param string $collectionTitle
     * @return AssetAssetCollectionConditionGenerator
     */
    public function isInCollection($collectionTitle)
    {
        return new AssetAssetCollectionConditionGenerator($collectionTitle);
    }

    /**
     * @return AssetWithoutAssetCollectionConditionGenerator
     */
    public function isWithoutCollection()
    {
        return new AssetWithoutAssetCollectionConditionGenerator();
    }

}