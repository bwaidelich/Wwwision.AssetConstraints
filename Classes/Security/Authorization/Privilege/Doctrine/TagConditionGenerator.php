<?php
namespace Wwwision\AssetConstraints\Security\Authorization\Privilege\Doctrine;

use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\ConditionGenerator as EntityConditionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\PropertyConditionGenerator;
use Neos\Flow\Security\Exception\InvalidPrivilegeException;
use Neos\Media\Domain\Model\Tag;

/**
 * A SQL condition generator, supporting special SQL constraints for tags
 */
class TagConditionGenerator extends EntityConditionGenerator
{
    /**
     * @var string
     */
    protected $entityType = Tag::class;

    /**
     * @param string $entityType
     * @return boolean
     * @throws InvalidPrivilegeException
     */
    public function isType($entityType)
    {
        throw new InvalidPrivilegeException('The isType() operator must not be used in Tag privilege matchers!', 1417083500);
    }

    /**
     * @param string $tagLabel
     * @return PropertyConditionGenerator
     */
    public function isLabeled($tagLabel)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('label');

        return $propertyConditionGenerator->equals($tagLabel);
    }
}
