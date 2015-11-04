<?php
namespace Wwwision\AssetConstraints\Aspects;


use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Aop\JoinPointInterface;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Property\PropertyMapper;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Media\Domain\Model\Asset;
use TYPO3\Media\Domain\Model\AssetCollection;
use TYPO3\Media\Domain\Repository\AssetCollectionRepository;
use TYPO3\Neos\Controller\Backend\ContentController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * AOP Aspect that hooks into uploading of assets in order to set the AssetCollection based on the current node
 *
 * It will look for the closest node matching the "Wwwision.AssetConstraints.nodeLookup.nodeFilter" setting (the closest Document node by default)
 * and check whether that node has a property called "assetCollection" to add the uploaded asset to that collection (the name of the property can be changed
 * via the "Wwwision.AssetConstraints.nodeLookup.propertyName" setting)
 *
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class ContentControllerAspect
{

    /**
     * @Flow\Inject
     * @var PropertyMapper
     */
    protected $propertyMapper;

    /**
     * @Flow\Inject
     * @var AssetCollectionRepository
     */
    protected $assetCollectionRepository;

    /**
     * @Flow\InjectConfiguration("nodeLookup.nodeFilter")
     * @var string
     */
    protected $lookupNodeFilter;

    /**
     * @Flow\InjectConfiguration("nodeLookup.propertyName")
     * @var string
     */
    protected $lookupPropertyName;

    /**
     * @Flow\Before("method(TYPO3\Neos\Controller\Backend\ContentController->uploadAssetAction())")
     * @param JoinPointInterface $joinPoint The current join point
     * @return void
     */
    public function rewriteSiteAssetCollection(JoinPointInterface $joinPoint)
    {
        if ($this->lookupNodeFilter === NULL || $this->lookupPropertyName === NULL) {
            return;
        }

        /** @var ContentController $contentController */
        $contentController = $joinPoint->getProxy();
        /** @var ActionRequest $actionRequest */
        $actionRequest = ObjectAccess::getProperty($contentController, 'request', TRUE);

        $nodeContextPath = $actionRequest->getInternalArgument('__node');
        if ($nodeContextPath === NULL) {
            return;
        }

        $node = $this->propertyMapper->convert($nodeContextPath, NodeInterface::class);

        $flowQuery = new FlowQuery(array($node));
        /** @var NodeInterface $documentNode */
        $documentNode = $flowQuery->closest($this->lookupNodeFilter)->get(0);

        if (!$documentNode->hasProperty($this->lookupPropertyName)) {
            return;
        }
        /** @var AssetCollection $assetCollection */
        $assetCollection = $this->assetCollectionRepository->findByIdentifier($documentNode->getProperty($this->lookupPropertyName));
        if ($assetCollection === NULL) {
            return;
        }

        /** @var Asset $asset */
        $asset = $joinPoint->getMethodArgument('asset');
        $assetCollection->addAsset($asset);
        $this->assetCollectionRepository->update($assetCollection);
    }

}