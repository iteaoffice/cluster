<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Controller\Admin;

use Cluster\Controller\Plugin\GetFilter;
use Cluster\Entity;
use Cluster\Service;
use Contact\Entity\Contact;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Service\GeneralService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Paginator\Paginator;
use Laminas\Validator\File\ImageSize;
use Laminas\Validator\File\MimeType;
use Laminas\View\Model\ViewModel;
use Search\Form\SearchFilter;

/**
 * @method Identity|Contact identity()
 * @method FlashMessenger flashMessenger()
 * @method GetFilter getClusterFilter()
 */
final class ClusterController extends AbstractActionController
{
    private Service\ClusterService $clusterService;
    private Service\FormService $formService;
    private GeneralService $generalService;
    private TranslatorInterface $translator;

    public function __construct(Service\ClusterService $clusterService, Service\FormService $formService, GeneralService $generalService, TranslatorInterface $translator)
    {
        $this->clusterService = $clusterService;
        $this->formService    = $formService;
        $this->generalService = $generalService;
        $this->translator     = $translator;
    }

    public function listAction(): ViewModel
    {
        $page         = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getClusterFilter();
        $clusterQuery = $this->clusterService
            ->findFiltered(Entity\Cluster::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($clusterQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 25);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new SearchFilter();
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel(
            [
                'paginator'     => $paginator,
                'form'          => $form,
                'encodedFilter' => urlencode($filterPlugin->getHash()),
                'order'         => $filterPlugin->getOrder(),
                'direction'     => $filterPlugin->getDirection(),
            ]
        );
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Entity\Cluster::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/cluster/list');
            }

            if ($form->isValid()) {
                /* @var $cluster Entity\Cluster */
                $cluster = $form->getData();
                $this->clusterService->save($cluster);

                $fileData = $this->params()->fromFiles();

                if (! empty($fileData['file']['name'])) {
                    $logo = new Entity\Cluster\Logo();
                    $logo->setCluster($cluster);
                    $logo->setClusterLogo(file_get_contents($fileData['file']['tmp_name']));
                    $imageSizeValidator = new ImageSize();
                    $imageSizeValidator->isValid($fileData['file']);

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['file']);
                    $logo->setContentType(
                        $this->generalService->findContentTypeByContentTypeName($fileTypeValidator->type)
                    );
                    $cluster->setLogo($logo);
                    $this->clusterService->save($logo);
                }

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('txt-cluster-has-been-created-successfully'));

                return $this->redirect()->toRoute(
                    'zfcadmin/cluster/view',
                    [
                        'id' => $cluster->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        $cluster = $this->clusterService->find(Entity\Cluster::class, (int)$this->params('id'));

        if (null === $cluster) {
            return $this->notFoundAction();
        }

        $data = $this->getRequest()->getPost()->toArray();
        $form = $this->formService->prepare($cluster, $data);

        if ($this->clusterService->canDeleteCluster($cluster)) {
            $form->remove('delete');
        }

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute(
                    'zfcadmin/cluster/view',
                    [
                        'id' => $cluster->getId(),
                    ]
                );
            }

            if (isset($data['delete']) && $this->clusterService->canDeleteCluster($cluster)) {
                $this->clusterService->delete($cluster);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('txt-cluster-has-been-deleted-successfully'));

                return $this->redirect()->toRoute(
                    'zfcadmin/cluster/list'
                );
            }

            if ($form->isValid()) {
                /* @var $cluster Entity\Cluster */
                $cluster = $form->getData();

                $this->clusterService->save($cluster);

                $fileData = $this->params()->fromFiles();

                if (! empty($fileData['file']['name'])) {
                    $logo = $cluster->getLogo();
                    if (! $logo) {
                        // Create a new logo element
                        $logo = new Entity\Cluster\Logo();
                        $logo->setCluster($cluster);
                    }
                    $logo->setClusterLogo(file_get_contents($fileData['file']['tmp_name']));
                    $imageSizeValidator = new ImageSize();
                    $imageSizeValidator->isValid($fileData['file']);

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['file']);
                    $logo->setContentType(
                        $this->generalService->findContentTypeByContentTypeName($fileTypeValidator->type)
                    );
                    $this->clusterService->save($logo);
                }

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('txt-cluster-has-been-updated-successfully'));

                return $this->redirect()->toRoute(
                    'zfcadmin/cluster/view',
                    [
                        'id' => $cluster->getId(),
                    ]
                );
            }
        }

        return new ViewModel([
            'form'    => $form,
            'cluster' => $cluster
        ]);
    }

    public function viewAction(): ViewModel
    {
        $cluster = $this->clusterService->find(Entity\Cluster::class, (int)$this->params('id'));

        if (null === $cluster) {
            return $this->notFoundAction();
        }

        return new ViewModel(['cluster' => $cluster]);
    }
}
