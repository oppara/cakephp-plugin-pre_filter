<?php
App::uses('Controller', 'Controller');
App::uses('TrimComponent', 'PreFilter.Controller/Component');

class TrimTestController extends Controller
{
    public $uses = false;

    public $components = array(
        'PreFilter.Trim',
    );
}

class TrimComponentTest extends CakeTestCase
{

    protected $data = array(
        'test' => " test 01\0",
        'Foo' => array(
            'bar' => "   n\0a me\0 　",
            'baz' => '　 bazbaz ',
        ),
        'Hoge' => array(
            '0' => array(
                'hoge' => 'ho　ge',
                'moge' => ' 　moge　 ',
                'hage' => '　 hage  　',
            ),
        ),
    );

    protected $expected = array(
        'test' => 'test 01',
        'Foo' => array(
            'bar' => 'na me',
            'baz' => 'bazbaz',
        ),
        'Hoge' => array(
            '0' => array(
                'hoge' => 'ho　ge',
                'moge' => 'moge',
                'hage' => 'hage',
            ),
        ),
    );

    private $enc = 'UTF-8';

    public function setUp()
    {
        parent::setUp();

        $request = new CakeRequest(null, false);
        $this->Controller = new TrimTestController($request, $this->getMock('CakeResponse'));
        $this->Controller->constructClasses();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Controller, $this->Component);
    }

    /**
     * @test
     */
    public function testTrim()
    {
        $this->Controller->request->data = $this->data;
        $this->Controller->request->query = $this->data;
        $this->Controller->Trim->initialize($this->Controller);
        $this->assertEqual($this->Controller->request->data, $this->expected);
        $this->assertEqual($this->Controller->request->query, $this->expected);
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Invalid encoding foo
     */
    public function testException()
    {
        $this->Controller->Trim->settings['encoding'] = 'foo';
        $this->Controller->request->data = $this->data;
        $this->Controller->Trim->initialize($this->Controller);
    }

    /**
     * @test
     */
    public function testTrimShiftJis()
    {
        $enc = 'SJIS-win';
        $this->Controller->Trim->settings['encoding'] = $enc;

        $data = $this->data;
        $expected = $this->expected;
        mb_convert_variables($enc, $this->enc, $data);
        mb_convert_variables($enc, $this->enc, $expected);

        $this->Controller->request->data = $data;
        $this->Controller->Trim->initialize($this->Controller);
        $this->assertEqual($this->Controller->request->data, $expected);
    }
}
