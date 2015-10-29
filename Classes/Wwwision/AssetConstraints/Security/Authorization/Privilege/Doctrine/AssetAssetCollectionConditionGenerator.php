<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Filter\SQLFilter as DoctrineSqlFilter;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\SqlGeneratorInterface;

/**
 * TODO
 */
class AssetAssetCollectionConditionGenerator implements SqlGeneratorInterface {

	/**
	 * @Flow\Inject
	 * @var ObjectManager
	 */
	protected $entityManager;

	/**
	 * @var string
	 */
	protected $collectionTitle;

	/**
	 * @param string $collectionTitle
	 */
	public function __construct($collectionTitle) {
		$this->collectionTitle = $collectionTitle;
	}

	/**
	 * @param DoctrineSqlFilter $sqlFilter
	 * @param ClassMetadata $targetEntity Metadata object for the target entity to create the constraint for
	 * @param string $targetTableAlias The target table alias used in the current query
	 * @return string
	 */
	public function getSql(DoctrineSqlFilter $sqlFilter, ClassMetadata $targetEntity, $targetTableAlias) {
		$quotedCollectionTitle = $this->entityManager->getConnection()->quote($this->collectionTitle);
		return $targetTableAlias . '.persistence_object_identifier IN (
			SELECT ' . $targetTableAlias . '_a.persistence_object_identifier
			FROM typo3_media_domain_model_asset AS ' . $targetTableAlias . '_a
			LEFT JOIN typo3_media_domain_model_assetcollection_assets_join ' . $targetTableAlias . '_acj ON ' . $targetTableAlias . '_a.persistence_object_identifier = ' . $targetTableAlias . '_acj.media_asset
			LEFT JOIN typo3_media_domain_model_assetcollection ' . $targetTableAlias . '_ac ON ' . $targetTableAlias . '_ac.persistence_object_identifier = ' . $targetTableAlias . '_acj.media_assetcollection
			WHERE ' . $targetTableAlias . '_ac.title = ' . $quotedCollectionTitle . ')';
	}
}