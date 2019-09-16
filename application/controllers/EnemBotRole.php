<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/BotManagement.php';

class EnemBotRole extends RestManager {
	private $prefixName = 'enemBot';

    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
		$this->CrudManagement = new CrudManagement();
		$this->BotManagement = new BotManagement();
    }

    public function superadmin_get()
    {
        $flag = 0;
        $queryString = $this->input->get(); // Query String for filter data :)

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
		];

        $dataModel = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'fieldTarget' => 'name',
                'queryString' => $queryString,
                'dataMaster' => []
            ]
        ];

        if (isset($queryString) && count($queryString) > 0) {
            foreach ($queryString as $key => $value) {
                if (!$value)
                {
                    $queryString[$key] = 'null';
                }
            }

            $dataModel[0]['filter'] = 'create_sql';
            $dataModel[0]['filterKey'] = 'name like "%'.$queryString['q'].'%" or id like "%'.$queryString['role'].'%"';
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_post()
    {
        $flag = 0;
		
		$enemKey       		= $this->enem_templates->anti_injection(strtolower($this->post('enemKey')));
		$enemAmountOrId    	= $this->enem_templates->anti_injection(strtolower($this->post('enemAmountOrId')));

		$data = $this->BotManagement->run([
			'enemKey' => $enemKey,
			'enemAmountOrId' => $enemAmountOrId,
			'configData' => [
				'className' => 'Role',
				'modelName' => 'RoleModel',
				'tableName' => 'user_role',
				'fieldName' => 'name',
				'keyPrefix' => $this->prefixName
			]
		], function ($index) {
			$prefix = $this->prefixName;
			$number = $index + 1;
			$name = $prefix . $number;
			
			$db = [
				'name' => $name,
				'description' => 'description'
			];

			return $db;
		});

		$flag = $data['flag'];
		unset($data['flag']);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_put()
    {
        $flag = 0;
        $name = $this->put('name');
        $description = $this->put('description');

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'description' => $description
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_delete()
    {
		$flag = 0;
		$dataRaw = json_decode($this->input->raw_input_stream, true);
		
		$enemKey       		= $this->enem_templates->anti_injection(strtolower($dataRaw['enemKey']));
		$enemAmountOrId    	= $this->enem_templates->anti_injection(strtolower($dataRaw['enemAmountOrId']));

        $data = $this->BotManagement->run([
			'enemKey' => $enemKey,
			'enemAmountOrId' => $enemAmountOrId,
			'configData' => [
				'className' => 'Role',
				'modelName' => 'RoleModel',
				'tableName' => 'user_role',
				'fieldName' => 'name',
				'keyPrefix' => $this->prefixName
			]
		]);

		$flag = $data['flag'];
		unset($data['flag']);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post() 
    {
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys post :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }
}
