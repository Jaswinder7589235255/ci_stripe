<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();

		$this->secret_key = "sk_test_51Inyo4SDMJp5hpGQzUGfILBmNnq2nXYcsexJ6YVATFhaiRErmuEWpFbErlK9iAZXURZpVp0EFYFMNtNzq00WYy2700r2LxFfua";
		$this->publisher_key = "pk_test_51Inyo4SDMJp5hpGQcuYCqNar2L1CB8SCw1SchVwIySgi6oJfG4ZozRjD104HvYeqR2euVXTRcecP78bLnVakkXCb00l4Ds7uc9";

		$this->stripeService = new \Stripe\Stripe();
        $this->stripeService->setVerifySslCerts(false);
        $this->stripeService->setApiKey($this->secret_key);

		$this->app = new \CodeIgniter\HTTP\CURLRequest(
				new \Config\App(),
				new \CodeIgniter\HTTP\URI(),
				new \CodeIgniter\HTTP\Response(new \Config\App())
		);
	}
}
