<?php


namespace Modules\ReportsKsnb\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\MongodbCore\Entities\KsnbCodeErrors;
use Modules\MongodbCore\Repositories\GroupRoleRepository;
use Modules\MongodbCore\Repositories\KsnbCodeErrorsRepository;
use Illuminate\Support\Facades\Validator;
use Modules\AssetTienNgay\Http\Controllers;
use Modules\MongodbCore\Repositories\RoleRepository;
use Modules\MongodbCore\Repositories\StoreRepository;
use Modules\MongodbCore\Repositories\UserCpanelRepository;

class KsnbCodeErrorsController extends BaseController
{
 /**
    * Modules\MysqlCore\Repositories\VANRepository
    */
    /**
    * Modules\MongodbCore\Repositories\ContractRepository
    */

    /**
    * Modules\MongodbCore\Repositories\TemporaryPlanRepository
    */

    private $ksnbCodeErrorsRepository;
    private $groupRoleRepository;
    private $roleRepository;
    private $storeRepository;

    public function __construct(
        KsnbCodeErrorsRepository $ksnbCodeErrorsRepository,
        GroupRoleRepository $groupRoleRepository,
        RoleRepository $roleRepository,
        StoreRepository $storeRepository,
        UserCpanelRepository $userRepository
    ) {
        $this->ksnbCodeErrorsRepository = $ksnbCodeErrorsRepository;
        $this->groupRoleRepository = $groupRoleRepository;
        $this->roleRepository = $roleRepository;
        $this->storeRepository =$storeRepository;
        $this->userRepo = $userRepository;
    }

    public function create(Request $request)
    {
        $inputData = $request->all();
        Log::channel('reportsksnb')->info("create" . print_r($inputData, true));
        $validate = Validator::make((array)$inputData,[
            'code_error'=>'required|string',
            'description'=>'required|string',
            'type'=>'required|string',
            'punishment'=>'required|string',
            'discipline'=>'required|string',
        ]);
        if ($validate->fails()){
            Log::channel('reportsksnb')->info('create validate' . $validate->errors()->first());
            return false;
        }
        $data = [
            KsnbCodeErrors::COLUMN_CODE_ERROR=>$request->code_error,
            KsnbCodeErrors::COLUMN_DESCRIPTION=>$request->description,
            KsnbCodeErrors::COLUMN_TYPE=>$request->type,
            KsnbCodeErrors::COLUMN_PUNISHMENT=>$request->punishment,
            KsnbCodeErrors::COLUMN_DISCIPLINE=>$request->discipline,
            KsnbCodeErrors::COLUMN_STATUS=>KsnbCodeErrors::COLUMN_BLOCK
        ];

        $ksnb_errors_create = $this->ksnbCodeErrorsRepository->createKsnbErrors($data);

        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $ksnb_errors_create
        ]);
    }

    public function update(Request $request, $id)
    {
        $inputData = $request->all();
        Log::channel('reportsksnb')->info('update' . print_r($inputData, true));
        $validate = Validator::make((array)$inputData, [
            'code_error' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|string',
            'punishment' => 'required|string',
            'discipline' => 'required|string',
        ]);

        if ($validate->fails()) {
            Log::channel('reportsksnb')->info('ksnbCodeErrors validate' . $validate->errors()->first());
            return response()->json([
                  BaseController::STATUS=>BaseController::HTTP_BAD_REQUEST,
                  BaseController::MESSAGE=>$validate->errors()->first(),
                  BaseController::DATA=>[]
            ]);
        }
        $data = [
            KsnbCodeErrors::COLUMN_CODE_ERROR=>$request->code_error,
            KsnbCodeErrors::COLUMN_DESCRIPTION=>$request->description,
            KsnbCodeErrors::COLUMN_TYPE=>$request->type,
            KsnbCodeErrors::COLUMN_PUNISHMENT=>$request->punishment,
            KsnbCodeErrors::COLUMN_DISCIPLINE=>$request->discipline,
        ];
        $ksnb_errors_update = $this->ksnbCodeErrorsRepository->updateKsnbErrors($data, $id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $ksnb_errors_update
        ]);
    }

    public function List()
    {
        $list_ksnb_errors = $this->ksnbCodeErrorsRepository->getAllKsnbErrors();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $list_ksnb_errors
        ]);
    }

    public function show($id)
    {
        $show_ksnb_errors = $this->ksnbCodeErrorsRepository->showKsnbErrors($id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $show_ksnb_errors
        ]);
    }

    public function ksnb_validate()
    {
        $result = $this->groupRoleRepository->getEmailGroupKsnb();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    public function status($id)
    {
        $result = $this->ksnbCodeErrorsRepository->update_status($id);
         return response()->json([
             BaseController::STATUS => BaseController::HTTP_OK,
             BaseController::MESSAGE => BaseController::SUCCESS,
             BaseController::DATA => $result
        ]);
    }

    //lấy mã lỗi theo type
    public function getCodeByType(Request $request) {
        log::channel("reportsksnb")->info("getCodeByType start");
        $type = $request->get('type');
        log::channel("reportsksnb")->info("getCodeByType type = " . $type);
        $getType = $this->ksnbCodeErrorsRepository->getCodeByType($type);
        $b = [];
        foreach($getType as $get) {
            $b[] = $get['code_error'];
        }
        $response = [
            "status" => 200,
            "message" => 'success',
            "data" => $b
        ];
        log::channel("reportsksnb")->info("getCodeByType response: " . print_r($response, true));
        return response()->json($response);
    }

    //lấy chế tài phạt theo code_error
    public function getPunishmentByCode(Request $request) {
        log::channel("reportsksnb")->info("getPunishmentByCode start");
        $code = $request->get('code_error');
        log::channel("reportsksnb")->info("getCodeByType code = " . $code);
        $getCode = $this->ksnbCodeErrorsRepository->getPunishmentByCode($code);
        $a = [];
        foreach($getCode as $get) {
            $a[] = $get['punishment_name'];
        }
        $response = [
            "status" => 200,
            "message" => 'success',
            "data" => $a
        ];
        log::channel("reportsksnb")->info("getPunishmentByCode response: " . print_r($response, true));
        return response()->json($response);
    }
    //lấy hình thức kỷ luật theo mã lỗi
    public function getDisciplineByCode(Request $request) {
        log::channel("reportsksnb")->info("getDisciplineByCode start");
        $code = $request->get('code_error');
        log::channel("reportsksnb")->info("getDisciplineByCode code = " . $code);
        $getCode = $this->ksnbCodeErrorsRepository->getDisciplineByCode($code);
        $c = [];
        foreach($getCode as $get) {
            $c[] = $get['discipline_name'];
        }
        $response = [
            "status" => 200,
            "message" => 'success',
            "data" => $c
        ];
        log::channel("reportsksnb")->info("getDisciplineByCode response: " . print_r($response, true));
        return response()->json($response);
    }


    // lấy tất cả  phòng giao dịch
    public function getAllStore()
    {
        $result = $this->storeRepository->getAll();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    //lấy tất cả các nhân viên kinh doanh
    public function getEmployeesByStoreId(Request $request)
    {
        $storeId = $request->get("store_id");
        $result  = $this->roleRepository->getEmailGroupNvkd($storeId);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    public function getEmailCHTByStoreId(Request $request)
    {
        $storeId = $request->get("store_id");
        if (!$storeId) {
            return response()->json([
                BaseController::STATUS => BaseController::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::ERRORS,
                BaseController::DATA => [],
            ]);
        }
        $emailCHT = null;
        $storeEmployees = $this->roleRepository->getEmailGroupNvkd($storeId);
        foreach ($storeEmployees as $email) {
            $isCHT = $this->groupRoleRepository->isCHT($email);
            if ($isCHT) {
                $emailCHT = $email;
                break;
            }
        }
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => [$emailCHT],
        ]);
    }
    //lấy mô tả mã lỗi
    public function getDescription(Request $request) {
        log::channel("reportsksnb")->info("getDescription start");
        $code = $request->get('code_error');
        log::channel("reportsksnb")->info("getDescription code = " . $code);
        $getCode = $this->ksnbCodeErrorsRepository->getDescription($code);
        $d = [];
        foreach($getCode as $get) {
            $d[] = $get['description'];
        }
        $response = [
            "status" => 200,
            "message" => 'success',
            "data" => $d
        ];
        log::channel("reportsksnb")->info("getDescription response: " . print_r($response, true));
        return response()->json($response);

    }

    //lấy name của email nv
    public function getNameByEmail(Request $request) {
        log::channel("reportsksnb")->info("getNameByEmail start");
        $user_name = $request->get('user_email');
        log::channel("reportsksnb")->info("getNameByEmail  = " . $user_name);
        $getName = $this->userRepo->getUserNameByEmail($user_name);
        $response = [
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            "data" => $getName
        ];
        log::channel("reportsksnb")->info("getNameByEmail response: " . print_r($response, true));
        return response()->json($response);
    }



    //mail nhân viên của các phòng ban
    public function allMailRoll($id)
    {
        $result = $this->roleRepository->getMailByRole($id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    public function getAllRoom()
    {
        $result = $this->roleRepository->getAllRoom();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    /**
    * Get error's information by code
    * @param Request $request
    * @return json
    */
    public function getErrorCodeInfo(Request $request)
    {
        $requestData = $request->all();
        Log::channel('reportsksnb')->info("getErrorCodeInfo". print_r($requestData, true));
        $errorCode = [];
        $data = [];
        if (isset($requestData['code'])) {
            $errorCode = $this->ksnbCodeErrorsRepository->getErrorCodeInfo($requestData['code']);
        }
        if ($errorCode) {
            $listCodes = $this->ksnbCodeErrorsRepository->getCodeByType($errorCode['type']);
            $data['item'] = $errorCode;
            $data['listCode'] = $listCodes;
            return response()->json([
                BaseController::STATUS => BaseController::HTTP_OK,
                BaseController::MESSAGE => BaseController::SUCCESS,
                BaseController::DATA => $data
            ]);
        }
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_BAD_REQUEST,
            BaseController::MESSAGE => BaseController::ERRORS,
            BaseController::DATA => []

        ]);
    }
}
