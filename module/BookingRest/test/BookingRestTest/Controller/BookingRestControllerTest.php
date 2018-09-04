<?php
namespace AlbumRestTest\Controller;

use Album\Model\Album;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumRestControllerTest extends AbstractHttpControllerTestCase {
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $albumTableMock;

    protected function setUp() {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../../config/application.config.php'
        );

        $this->albumTableMock = $this->getAlbumTableMock();
        $this->useAlbumTableMock($this->albumTableMock);
    }

    public function testGetListCanBeAccessed() {
        $this->albumTableMock
            ->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(array()));

        $this->dispatch('/album-rest', 'GET', array(), true);

        $this->assertResponseStatusCode(200);
        $this->assertIsAlbumRestController();
    }

    public function testGetCanBeAccessed() {
        $this->albumTableMock->expects($this->once())
            ->method('getAlbum')
            ->will($this->returnValue(array()));

        $this->dispatch('/album-rest', 'GET', array(
            'id' => 1
        ), true);

        $this->assertResponseStatusCode(200);
        $this->assertIsAlbumRestController();
    }

    public function testCreateCanBeAccessed() {
        $data = array(
            'artist' => 'foo',
            'title' => 'bar'
        );

        $this->albumTableMock
            ->expects($this->once())
            ->method('saveAlbum')
            ->with($this->withAlbumData($data))
            ->will($this->returnValue(123));

        $this->dispatch('/album-rest', 'POST', $data, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsAlbumRestController();
    }

    public function testUpdateCanBeAccessed() {
        $data_orig = array(
            'artist' => 'foo',
            'title' => 'bar'
        );

        $updateData = array(
            'title' => 'shazaam'
        );

        // Mock AlbumTable::getAlbum
        $this->albumTableMock
            ->expects($this->once())
            ->method('getAlbum')
            ->will($this->returnValue($data_orig));

        // Mock AlbumTable::saveAlbum
        $this->albumTableMock
            ->expects($this->once())
            ->method('saveAlbum')
            ->with($this->withAlbumData(array(
                'artist' => 'foo',
                'title' => 'shazaam'
            )))
            ->will($this->returnValue(123));

        $this->dispatch('/album-rest/1', 'PUT', $updateData, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsAlbumRestController();
    }

    public function testDeleteCanBeAccessed() {
        $this->albumTableMock
            ->expects($this->once())
            ->method('deleteAlbum')
            ->with(123);

        $this->dispatch('/album-rest/123', 'DELETE', null, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsAlbumRestController();
    }

    /*
     * HELPER METHODS
     */

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAlbumTableMock() {
        return $this->getMockBuilder('Album\Model\AlbumTable')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function useAlbumTableMock(\PHPUnit_Framework_MockObject_MockObject $albumTableMock) {
        $this->getApplicationServiceLocator()
            ->setAllowOverride(true)
            ->setService('Album\Model\AlbumTable', $albumTableMock);
    }

    protected function assertIsAlbumRestController() {
        $this->assertControllerName('AlbumRest\Controller\AlbumRest');
        $this->assertControllerClass('AlbumRestController');
        $this->assertMatchedRouteName('album-rest');
    }

    protected function withAlbumData($data) {
        return $this->callback(function ($obj) use ($data) {
            return $obj instanceof Album &&
            $obj->artist === $data['artist'] &&
            $obj->title === $data['title'];
        });

    }

}