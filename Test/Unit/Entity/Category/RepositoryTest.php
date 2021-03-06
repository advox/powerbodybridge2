<?php

namespace Powerbody\Bridge\Test\Unit\Entity\Category;

use Powerbody\Bridge\Api\Client;
use Powerbody\Bridge\Entity\Category\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItReturnsArrayOfResults()
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->expects($this->once())
            ->method('call')
            ->with('bridge.getAllCategories', ['for_dropclient' => true])
            ->willReturn(array([1], [2], [3]));

        $repo = new Repository($clientMock);

        $this->assertInternalType('array', $repo->findAll());
    }

    public function testItCastsResultToDataObjectsArray()
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->expects($this->once())
            ->method('call')
            ->with('bridge.getAllCategories', ['for_dropclient' => true])
            ->willReturn(array([1], [2], [3]));

        $repo = new Repository($clientMock);

        $this->assertContainsOnlyInstancesOf(\Magento\Framework\DataObject::class, $repo->findAll());
    }

    public function testItAlwaysReturnsArray()
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->expects($this->atLeastOnce())
            ->method('call')
            ->with('bridge.getAllCategories', ['for_dropclient' => true])
            ->willReturn(null);

        $repo = new Repository($clientMock);

        $this->assertInternalType('array', $repo->findAll());

        $clientMock->expects($this->once())
            ->method('call')
            ->with('bridge.getAllCategories')
            ->willReturn(new \stdClass());

        $this->assertInternalType('array', $repo->findAll());
    }
}
