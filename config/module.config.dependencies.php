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

namespace Cluster;

use Affiliation\Service\AffiliationService;
use Contact\Service\ContactService;
use Contact\Service\SelectionContactService;
use Doctrine\ORM\EntityManager;
use Cluster\Options\ModuleOptions;
use Cluster\Service\ClusterService;
use Cluster\Service\FormService;
use General\Navigation\Service\NavigationService;
use General\Service\CountryService;
use General\Service\GeneralService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use Program\Service\CallService;
use Project\Search\Service\ProjectSearchService;
use Project\Service\ProjectService;
use Project\Service\ReportService;
use Project\Service\VersionService;
use ZfcTwig\View\TwigRenderer;

return [
    ConfigAbstractFactory::class => [
        // Controllers
        Controller\ClusterController::class                    => [
            Service\ClusterService::class,
        ],

    ]
];
