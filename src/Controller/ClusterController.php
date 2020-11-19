<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace Cluster\Controller;

use Cluster\Controller\Plugin\GetFilter;
use Cluster\Entity;
use Cluster\Service;
use Contact\Entity\Contact;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Paginator\Paginator;
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
    private TranslatorInterface $translator;

    public function __construct(Service\ClusterService $clusterService, Service\FormService $formService, TranslatorInterface $translator)
    {
        $this->clusterService = $clusterService;
        $this->formService    = $formService;
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

                $result = $this->clusterService->save($cluster);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('txt-custer-has-been-created-successfully'));

                return $this->redirect()->toRoute(
                    'zfcadmin/cluster/view',
                    [
                        'id' => $result->getId(),
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

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('txt-cluster-has-been-updated-successfully'));

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

    public function viewAction(): ViewModel
    {
        $cluster = $this->clusterService->find(Entity\Cluster::class, (int)$this->params('id'));

        if (null === $cluster) {
            return $this->notFoundAction();
        }

        return new ViewModel(['cluster' => $cluster]);
    }
}
