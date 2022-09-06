<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
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
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

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
     */
    protected $data = [];
    protected $rootView = '';
    protected $modelName = '';
    protected $model = null;
    protected $_auth = null;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        $this->data['_locale'] = $request->getLocale();
        $this->data['_auth'] = service('auth');
        $this->data['_root_view'] = $this->rootView ?? '';
        $this->data['_masterDatas'] = [];
        helper('main');
        if (can('manajemen_master')) {
            $this->data['_masterDatas'] = [
                [
                    'url' => 'account',
                    'permissions' => ['account_access']
                ],
            ];
        }
        $this->data['url_previous_page'] = $this->getPreviousPage();
        $request->dataPass = $this->data;
        $this->initialize();
        $auth = service('auth');
        $this->_auth = $auth;
        parent::initController($request, $response, $logger);
        // Preload any models, libraries, etc, here.

        $this->session = service('session');
        if(!empty($this->modelName)){
            $this->model = model($this->modelName);
        }
    }
    public function initialize()
    {
        
    }
    protected function getDataRequest($filtering = true)
    {
        $request = $this->request;
        /** @var IncomingRequest $request */
        if (strpos($request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $data = $request->getJSON(true);
        }else{
            if (
                in_array($request->getMethod(), ['put', 'patch', 'delete'], true)
                && strpos($request->getHeaderLine('Content-Type'), 'multipart/form-data') === false
            ) {
                $data = $request->getRawInput();
            } else {
                $data = $request->getVar() ?? [];
            }
        }
        $data = (array) array_merge((array)$data, $request->getFiles() ?? []);
        if($filtering){
            return $this->filteringData($data);
        }else{
            return $data;
        }
    }
    protected function filteringData($data)
    {
        foreach ($data as &$value) {
            if (is_string($value)) {
                $value = htmlspecialchars($value, true);
            }
        }
        unset($value);
        return $data;
    }

    protected function datatable_get($params = [],$model = null)
    {
        if($model === null){
            $model = $this->model;
        }
        $rules = [
            'length' => [
                'label'  => "Length",
                'rules'  => "required",
                'errors' => []
            ],
            'start' => [
                'label'  => "Start",
                'rules'  => "required",
                'errors' => []
            ],
            'order' => [
                'label'  => "Order",
                'rules'  => "required",
                'errors' => []
            ],
            'columns' => [
                'label'  => "Columns",
                'rules'  => "required",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()])->send();
            die();
        }
        $data = $this->getDataRequest();
        $limit = $data['length']; // Ambil data limit per page
        $start = $data['start']; // Ambil data start
        $order_index = $data['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
        $orderBy = $data['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
        $ordered = $data['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
        $sql_total = $model->count_all($params); // Panggil fungsi count_all pada Admin
        $sql_data = $model->filter($limit, $start, $orderBy, $ordered, $params); // Panggil fungsi filter pada Admin
        $no = $start + 1;
        foreach ($sql_data as &$sql_d) {
            $sql_d = $sql_d->toArrayDatatable(false,true,true);
            $sql_d['no'] = $no;
            $no++;
        }
        unset($sql_d);
        $sql_filter = $model->count_all($params); // Panggil fungsi count_filter pada Admin
        $callback = [
            'draw' => $data['draw'], // Ini dari datatablenya
            'recordsTotal' => $sql_total,
            'recordsFiltered' => $sql_filter,
            'data' => $sql_data
        ];
        return $callback;
    }
    public function getPreviousPage($back = 1)
    {
        $url = service('request')->uri->getSegments();
        $uri = $url;
        for ($i = 0; $i <= $back; $i++) {
            unset($uri[sizeof($url) - $i]);
        }
        $uri = implode('/', $uri);
        return base_url($uri);
    }
}