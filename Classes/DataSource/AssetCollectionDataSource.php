<?php
namespace Wwwision\AssetConstraints\DataSource;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Neos\Service\DataSource\AbstractDataSource;

/**
 * A Data source that allows to render a selector for all AssetCollections in the Neos inspector
 *
 * Usage (in NodeTypes.yaml):
 *
 * 'Some.Node:Type':
 *   properties:
 *     'propertyName':
 *       ui:
 *         inspector:
 *           editor: 'Content/Inspector/Editors/SelectBoxEditor'
 *           editorOptions:
 *             dataSourceIdentifier: 'wwwision-assetconstraints-assetcollections'
 */
class AssetCollectionDataSource extends AbstractDataSource
{
    /**
     * @var string
     */
    static protected $identifier = 'wwwision-assetconstraints-assetcollections';

    /**
     * @Flow\Inject
     * @var AssetCollectionRepository
     */
    protected $assetCollectionRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Get data
     *
     * @param NodeInterface $node unused
     * @param array $arguments unused
     * @return array title of all asset collections
     */
    public function getData(NodeInterface $node = null, array $arguments)
    {
        $assetCollections = [];
        /** @var AssetCollection $assetCollection */
        foreach ($this->assetCollectionRepository->findAll() as $assetCollection) {
            $assetCollections[] = ['value' => $this->persistenceManager->getIdentifierByObject($assetCollection), 'label' => $assetCollection->getTitle()];
        }

        return $assetCollections;
    }
}
