<?php

namespace Torpedo\Test;
use Torpedo\Request;

class RequestTest extends \PHPUnit_Framework_TestCase {
	protected $request; 
	protected $server = [
		'SERVER_PROTOCOL' => '1.0',
		'REQUEST_METHOD' => 'GET',
		'REQUEST_URI' => '/index.php',
		'REQUEST_URI_PATH' => '/index.php',
		'HTTPS' => FALSE,
		'HTTP_MY_HEADER' => 'my value'
	];

	protected $get = [ 
		'name' => 'nascho'
	];

	// set up value for global scope
	public function setup(){
		$this->request = new \Torpedo\Request(
			$this->get, [], [], $this->server, []
		);
	}

	// check if HTTP method is get
	public function testHttpRequestMethodIsGet(){
		$this->assertEquals($this->request->isGet(), true);
	}

	// check if HTTP method is POST
	public function testHttpRequestMethodIsPost(){
		$this->assertEquals($this->request->isPost(), false);
	}

	// check if HTTP method is PUT
	public function testHttpRequestMethodIsPut(){
		$this->assertEquals($this->request->isPost(), false);
	}

	// check if HTTP method is delete
	public function testHttpRequestMethodIsDelete(){
		$this->assertEquals($this->request->isPost(), false);
	}

	// check if GET value is same as assigned 
	public function testGetVariableFromGetRequest(){
		$this->assertEquals($this->request->get('name'), 'nascho');
	}

	// test that POST value is null 
	// request method is GET only for this example
	public function testPostVariableFromPostRequest(){
		$this->assertEquals($this->request->post('name'), null);
	}

	// test server protocol returns the correct version
	public function testServerProtocolReturnsResult(){
		$this->assertEquals($this->request->server('SERVER_PROTOCOL'), '1.0');
	}

	// test reques URI is same as assigned
	public function testRequestUriReturnsResult(){
		$this->assertEquals($this->request->server('REQUEST_URI'), '/index.php');
	}

	// http referer is not declared, so check this test returns null
	public function testServerRefererReturnsNull(){
		$this->assertEquals($this->request->server('HTTP_REFERER'), null);
	}

	// check request type returns GET method. 
	public function testGetMethodReturnsRequestMethodResult(){
		$this->assertEquals($this->request->server('REQUEST_METHOD'), 'GET');
	}
	
	// test if connection is authenticated / HTTPS
	public function testConnectionIsViaHTTPS(){
		$this->assertEquals($this->request->server('HTTPS'), FALSE);
	}
	
	// assert false that this is an AJAX request. 
	public function testRequestMethodHasNoHxrObject(){
		$this->assertNotEquals($this->request->server('REQUEST_METHOD'), 'XMLHttpRequest');
	}

}