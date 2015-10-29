<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\EntityPrivilege;
use TYPO3\Media\Domain\Model\Asset;
use Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine\AssetConditionGenerator;

/**
 * TODO
 */
class ReadAssetPrivilege extends EntityPrivilege {

	/**
	 * @param string $entityType
	 * @return boolean
	 */
	public function matchesEntityType($entityType) {
		return $entityType === Asset::class;
	}

	/**
	 * @return AssetConditionGenerator
	 */
	protected function getConditionGenerator() {
		return new AssetConditionGenerator();
	}
}