<?php

declare(strict_types=1);

namespace ClusterTest\Service;

use Cluster\Entity\Report;
use Cluster\Service\ClusterReportService;
use Cluster\View\Helper\Report\Progress;
use PHPUnit\Framework\MockObject\MockObject;
use Testing\Util\AbstractServiceTest;
use Laminas\I18n\Translator\TranslatorInterface;

class ProgressTest extends AbstractServiceTest
{
    public function testInvoke()
    {
        $clusterReport = new Report();

        /** @var ClusterReportService|MockObject $clusterReportServiceMock */
        $clusterReportServiceMock = $this->getMockBuilder(ClusterReportService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['parseCompletedPercentage'])
            ->getMock();

        $clusterReportServiceMock->expects($this->exactly(4))
            ->method('parseCompletedPercentage')
            ->with($clusterReport)
            ->will($this->onConsecutiveCalls(10.0, 50.0, 100.0, 100.0));

        /** @var TranslatorInterface|MockObject $translatorMock */
        $translatorMock = $this->getMockBuilder(TranslatorInterface::class)
            ->onlyMethods(['translate', 'translatePlural'])
            ->getMock();

        $translatorMock->expects($this->exactly(5))
            ->method('translate')
            ->will($this->returnArgument(0));

        $progress = new Progress($clusterReportServiceMock, $translatorMock);
        $template = $progress->getTemplate();

        $this->assertEquals(
            sprintf($template, 'danger', 10, 10, '10% txt-completed'),
            $progress($clusterReport)
        );

        $this->assertEquals(
            sprintf($template, 'warning', 50, 50, '50% txt-completed'),
            $progress($clusterReport)
        );

        $this->assertEquals(
            sprintf($template, 'success', 100, 100, '100% txt-completed'),
            $progress($clusterReport)
        );

        // Final
        $clusterReport->setFinal(true);
        $this->assertEquals(
            sprintf($template, 'success', 100, 100, '100% txt-completed + txt-final'),
            $progress($clusterReport)
        );
    }
}
