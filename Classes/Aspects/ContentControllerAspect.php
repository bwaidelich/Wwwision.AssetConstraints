<?php
namespace Wwwision\AssetConstraints\Aspects;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Property\PropertyMapper;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Neos\Controller\Backend\ContentController;
use Neos\Utility\ObjectAccess;

/**
 * AOP Aspect that hooks into uploading of assets in order to set the AssetCollection based on the current node
 * It will look for the closest node matching the "Wwwision.AssetConstraints.nodeLookup.nodeFilter" setting (the
 * closest Document node by default) and check whether that node has a property called "assetCollection" to add the
 * uploaded asset to that collection (the name of the property can be changed via the
 * "Wwwision.AssetConstraints.nodeLookup.propertyName" setting)
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
     * @Flow\Before("method(Neos\Neos\Controller\Backend\ContentController->uploadAssetAction())")
     * @param JoinPointInterface $joinPoint The current join point
     * @return void
     */
    public function rewriteSiteAssetCollection(JoinPointInterface $joinPoint)
    {
        if ($this->lookupNodeFilter === null || $this->lookupPropertyName === null) {
            return;
        }

        /** @var ContentController $contentController */
        $contentController = $joinPoint->getProxy();
        /** @var ActionRequest $actionRequest */
        $actionRequest = ObjectAccess::getProperty($contentController, 'request', true);

        $nodeContextPath = $actionRequest->getInternalArgument('__node');
        if ($nodeContextPath === null) {
            return;
        }

        $node = $this->propertyMapper->convert($nodeContextPath, NodeInterface::class);

        $flowQuery = new FlowQuery([$node]);
        /** @var NodeInterface $documentNode */
        $documentNode = $flowQuery->closest($this->lookupNodeFilter)->get(0);

        if (!$documentNode->hasProperty($this->lookupPropertyName)) {
            return;
        }
        /** @var AssetCollection $assetCollection */
        $assetCollection = $this->assetCollectionRepository->findByIdentifier($documentNode->getProperty($this->lookupPropertyName));
        if ($assetCollection === null) {
            return;
        }

        /** @var Asset $asset */
        $asset = $joinPoint->getMethodArgument('asset');
        $assetCollection->addAsset($asset);
        $this->assetCollectionRepository->update($assetCollection);
    }
}
