<?php
namespace Wwwision\AssetConstraints\DataSource;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\PersistenceManagerInterface;
use TYPO3\Media\Domain\Model\AssetCollection;
use TYPO3\Media\Domain\Repository\AssetCollectionRepository;
use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

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
    public function getData(NodeInterface $node = NULL, array $arguments)
    {
        $assetCollections = [];
        /** @var AssetCollection $assetCollection */
        foreach ($this->assetCollectionRepository->findAll() as $assetCollection) {
            $assetCollections[] = ['value' => $this->persistenceManager->getIdentifierByObject($assetCollection), 'label' => $assetCollection->getTitle()];
        }
        return $assetCollections;
    }
}
