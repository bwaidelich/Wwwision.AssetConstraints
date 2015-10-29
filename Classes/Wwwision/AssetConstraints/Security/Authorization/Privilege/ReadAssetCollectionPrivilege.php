<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\EntityPrivilege;
use TYPO3\Media\Domain\Model\AssetCollection;
use Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine\AssetCollectionConditionGenerator;

/**
 * TODO
 */
class ReadAssetCollectionPrivilege extends EntityPrivilege {

	/**
	 * @param string $entityType
	 * @return boolean
	 */
	public function matchesEntityType($entityType) {
		return $entityType === AssetCollection::class;
	}

	/**
	 * @return AssetCollectionConditionGenerator
	 */
	protected function getConditionGenerator() {
		return new AssetCollectionConditionGenerator();
	}
}