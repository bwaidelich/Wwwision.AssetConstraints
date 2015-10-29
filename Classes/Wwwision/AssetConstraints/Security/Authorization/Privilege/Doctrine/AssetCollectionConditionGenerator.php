<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\ConditionGenerator as EntityConditionGenerator;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\PropertyConditionGenerator;
use TYPO3\Flow\Security\Exception\InvalidPrivilegeException;
use TYPO3\Media\Domain\Model\AssetCollection;

/**
 * A SQL condition generator, supporting special SQL constraints for asset collections
 */
class AssetCollectionConditionGenerator extends EntityConditionGenerator {

	/**
	 * @var string
	 */
	protected $entityType = AssetCollection::class;

	/**
	 * @param string $entityType
	 * @return boolean
	 * @throws InvalidPrivilegeException
	 */
	public function isType($entityType) {
		throw new InvalidPrivilegeException('The isType() operator must not be used in AssectCollection privilege matchers!', 1445941247);
	}

	/**
	 * @param string $collectionTitle
	 * @return PropertyConditionGenerator
	 */
	public function isTitled($collectionTitle) {
		$propertyConditionGenerator = new PropertyConditionGenerator('title');
		return $propertyConditionGenerator->equals($collectionTitle);
	}

}