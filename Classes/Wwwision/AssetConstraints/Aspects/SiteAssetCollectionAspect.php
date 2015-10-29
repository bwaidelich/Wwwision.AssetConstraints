<?php
namespace Wwwision\AssetConstraints\Aspects;


use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Aop\JoinPointInterface;
use TYPO3\Media\Domain\Model\AssetCollection;
use TYPO3\Media\Domain\Repository\AssetCollectionRepository;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class SiteAssetCollectionAspect {

	/**
	 * @Flow\Inject
	 * @var AssetCollectionRepository
	 */
	protected $assetCollectionRepository;

	/**
	 * @Flow\Around("method(TYPO3\Neos\Domain\Model\Site->getAssetCollection())")
	 * @param JoinPointInterface $joinPoint The current join point
	 * @return AssetCollection
	 */
	public function rewriteSiteAssetCollection(JoinPointInterface $joinPoint) {
		return $this->assetCollectionRepository->findOneByTitle('some-collection');
//		/** @var \TYPO3\Flow\Mvc\ActionRequest $request */
//		$request = $joinPoint->getProxy()->getRequest();
//		$arguments = $joinPoint->getMethodArguments();
//
//		$currentNode = $request->getInternalArgument('__node');
//		if (!$request->getMainRequest()->hasArgument('node') || !$currentNode instanceof Node) {
//			return $joinPoint->getAdviceChain()->proceed($joinPoint);
//		}
//
//		$currentNode = $request->getInternalArgument('__node');
//		$controllerObjectName = $this->getControllerObjectName($request, $arguments);
//		$actionName = $arguments['actionName'] !== NULL ? $arguments['actionName'] : $request->getControllerActionName();
//
//		$targetNode = $this->pluginService->getPluginNodeByAction($currentNode, $controllerObjectName, $actionName);
//
//		// TODO override namespace
//
//		$q = new FlowQuery(array($targetNode));
//		$pageNode = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
//		$result = $this->generateUriForNode($request, $joinPoint, $pageNode);
//
//		return $result;
	}

}