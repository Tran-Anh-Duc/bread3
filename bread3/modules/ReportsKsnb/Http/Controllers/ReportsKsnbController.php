<?php

namespace Modules\ReportsKsnb\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Modules\MongodbCore\Repositories\KsnbRepositoryInterface as KsnbRepository;
use Modules\MongodbCore\Repositories\GroupRoleRepository;
use Modules\MongodbCore\Repositories\Interfaces\KsnbRepositoryInterface as KsnbRepository;
use Modules\MongodbCore\Entities\ReportsKsnb;
use DateTime;

use Modules\MongodbCore\Repositories\StoreRepository;
use Modules\ReportsKsnb\Service\ApiCall;
use Modules\ReportsKsnb\Service\ksnbApi;
use Modules\MongodbCore\Repositories\RoleRepository;
use Modules\MongodbCore\Entities\KsnbCodeError;
use Modules\MongodbCore\Entities\UserCpanel;
use Modules\MongodbCore\Repositories\Interfaces\UserCpanelRepositoryInterface as userCpanelRepository;




class ReportsKsnbController extends BaseController
{
    private $ksnbRepository;
    // private $ksnbCodeErrorsRepository;

    /**
    * Modules\MysqlCore\Repositories\VANRepository
    */
    /**
    * Modules\MongodbCore\Repositories\ContractRepository
    */

    /**
    * Modules\MongodbCore\Repositories\TemporaryPlanRepository
    */

    private $ksnbRepo;
    private $roleRepository;
    private $storeRepository;
    private $groupRoleRepository;
    private $userCpanelRepository;
    // private $ksnbCodeRepo;



   /**
     * @OA\Info(
     *     version="1.0",
     *     title="API VFCPayment"
     * )
     */
    public function __construct(

        KsnbRepository $ksnbRepository,
        RoleRepository $roleRepository,
        StoreRepository $storeRepository,
        GroupRoleRepository $groupRoleRepository,
        UserCpanelRepository $userCpanelRepository
    )

    {
        $this->ksnbRepo = $ksnbRepository;
        $this->roleRepository = $roleRepository;
        $this->storeRepository = $storeRepository;
        $this->groupRoleRepository = $groupRoleRepository;
        $this->userCpanelRepo = $userCpanelRepository;
    }


    public function saveReport(Request $request) {
        $requestData = $request->all();
        Log::channel('reportsksnb')->info("createReport". print_r($requestData, true));
        $validator = Validator::make($requestData, [
            'code_error' => 'required',
            'type' => 'required',
            'punishment' => 'required',
            'discipline' => 'required',
            'user_name' => 'required',
            'user_email' => 'required',
            'store_name' => 'required',
            'url' => 'required',
        ], [
            'code_error.required' => 'M?? l???i kh??ng ???????c ????? tr???ng',
            'type.required' => 'Nh??m vi ph???m kh??ng ???????c ????? tr???ng',
            'punishment.required' => 'Ch??? t??i ph???t kh??ng ???????c ????? tr???ng',
            'discipline.required' => 'Ch??? t??i ph???t kh??ng ???????c ????? tr???ng',
            'user_name.required' => 'T??n nh??n vi??n kh??ng ????? tr???ng',
            'user_email.required' => 'Email nh??n vi??n kh??ng ????? tr???ng',
            'store_name.required'=> 'T??n PGD kh??ng ???????c ????? tr???ng',
            'url.required' => '???nh kh??ng ????? tr???ng',
        ]);
        Log::channel('reportsksnb')->info("validator ". $validator->fails());
        if($validator->fails()) {
            Log::channel('reportsksnb')->info('createReport validator' .$validator->errors()->first());
            return response()->json([
                BaseController::STATUS => BaseController::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => $validator->errors()->first(),
            ]);
        }
        $storeId = $requestData['store_name'];
        $storeName = $this->roleRepository->getStoreName($storeId);
        $input = [
            ReportsKsnb::COLUMN_CODE_ERROR          => $requestData['code_error'],
            ReportsKsnb::COLUMN_DESCRIPTION         => $requestData['description'],
            ReportsKsnb::COLUMN_DESCRIPTION_ERROR   => isset($requestData['description_error']) ? $requestData['description_error']: "",
            ReportsKsnb::COLUMN_TYPE                => $requestData['type'],
            ReportsKsnb::COLUMN_IMAGE_PATH          => $requestData['url'],
            ReportsKsnb::COLUMN_STORE_ID            => $storeId,
            ReportsKsnb::COLUMN_STORE_NAME          => $storeName,
            ReportsKsnb::COLUMN_STORE_EMAIL_TPGD    => $requestData['email_tpgd'],
            ReportsKsnb::COLUMN_USER_NAME           => $requestData['user_name'],
            ReportsKsnb::COLUMN_USER_EMAIL          => $requestData['user_email'],
            ReportsKsnb::COLUMN_PUNISHMENT          => $requestData['punishment'],
            ReportsKsnb::COLUMN_DISCIPLINE          => $requestData['discipline'],
            ReportsKsnb::COLUMN_CREATED_BY          => $requestData['created_by'],
            ReportsKsnb::COLUMN_ID_ROOM             => $storeId,
        ];
        Log::channel('reportsksnb')->info('input data' . print_r($input, true));
        $createReport = $this->ksnbRepo->createReport($input);
        Log::channel('reportsksnb')->info('createReport' . print_r($createReport, true));
        if (!$createReport) {
            $response = [
                BaseController::STATUS => BaseController::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::ERROR,
                BaseController::DATA => $input
            ];
        } else {
            $response = [

                BaseController::STATUS => BaseController::HTTP_OK,
                BaseController::MESSAGE => BaseController::SUCCESS,
                BaseController::DATA => $createReport,

            ];
        }
        Log::channel('reportsksnb')->info('createReport response' . print_r($response, true));
        return response()->json($response);
    }

    public function updateReport(Request $request, $id) {
        $requestData = $request->all();
        Log::channel('reportsksnb')->info('updateReport' . print_r($requestData, true));
        $validator = Validator::make($requestData, [
            'code_error' => 'required',
            'type' => 'required',
            'punishment' => 'required',
            'discipline' => 'required',
            // // 'status' => 'required',
            'user_name' => 'required',
            'user_email' => 'required',
            'store_name' => 'required',
            'url' => 'required',
        ], [
            'code_error.required' => 'M?? l???i kh??ng ???????c ????? tr???ng',
            'type.required' => 'Nh??m vi ph???m kh??ng ???????c ????? tr???ng',
            'punishment.required' => 'Ch??? t??i ph???t kh??ng ???????c ????? tr???ng',
            'discipline.required' => 'Ch??? t??i ph???t kh??ng ???????c ????? tr???ng',
            'user_name.required' => 'T??n nh??n vi??n kh??ng ????? tr???ng',
            'user_email.required' => 'Email nh??n vi??n kh??ng ????? tr???ng',
            'store_name.required'=> 'T??n PGD kh??ng ???????c ????? tr???ng',
            'url.required' => '???nh kh??ng ????? tr???ng',
        ]);

        if($validator->fails()) {
            Log::channel('reportsksnb')->info('createReport validator' .$validator->errors()->first());
            return response()->json([
                BaseController::MESSAGE=>$validator->errors()->first()
            ]);
        }
        $storeId = $requestData['store_name'];
//        $storeName = $this->storeRepository->getStoreName($storeId);
          $storeName = $this->roleRepository->getStoreName($storeId);
        $input = [
            ReportsKsnb::COLUMN_CODE_ERROR          => $requestData['code_error'],
            ReportsKsnb::COLUMN_DESCRIPTION         => $requestData['description'],
            ReportsKsnb::COLUMN_DESCRIPTION_ERROR   => isset($requestData['description_error']) ? $requestData['description_error'] : "",
            ReportsKsnb::COLUMN_TYPE                => $requestData['type'],
            ReportsKsnb::COLUMN_STORE_ID            => $storeId,
            ReportsKsnb::COLUMN_STORE_NAME          => $storeName,
            ReportsKsnb::COLUMN_STORE_EMAIL_TPGD    => $requestData['email_tpgd'],
            ReportsKsnb::COLUMN_USER_NAME           => $requestData['user_name'],
            ReportsKsnb::COLUMN_USER_EMAIL          => $requestData['user_email'],
            ReportsKsnb::COLUMN_PUNISHMENT          => $requestData['punishment'],
            ReportsKsnb::COLUMN_DISCIPLINE          => $requestData['discipline'],

            ReportsKsnb::COLUMN_UPDATED_BY         => $requestData['updated_by'],
            ReportsKsnb::COLUMN_ID_ROOM              => $storeId,

            ReportsKsnb::COLUMN_UPDATED_BY          => $requestData['updated_by'],
            ReportsKsnb::COLUMN_UPDATED_BY          => $requestData['updated_by'],
            ReportsKsnb::COLUMN_IMAGE_PATH          => $requestData['url'],
            ReportsKsnb::COLUMN_ID_ROOM             => $storeId,
        ];

        Log::channel('reportsksnb')->info('input data' . print_r($input, true));
        $updateReport = $this->ksnbRepo->updateReport($input, $id);
        Log::channel('reportsksnb')->info('updateReport' . print_r($updateReport, true));
        if (!$updateReport) {
            $response = [
                BaseController::STATUS=>BaseController::HTTP_BAD_REQUEST,
                BaseController::MESSAGE=>BaseController::ERROR,
                BaseController::DATA=>[]
            ];
        } else {
            $response = [
                  BaseController::STATUS=>BaseController::HTTP_OK,
                  BaseController::MESSAGE=>BaseController::SUCCESS,
                  BaseController::DATA=>$input
            ];
        }
        Log::channel("reportsksnb")->info("updateReport response" . print_r($response, true));
        return response()->json($response);
    }

    public function listReport() {
        $listReports = $this->ksnbRepo->getAllReport();
        Log::channel('reportsksnb')->info('result listReports : '. print_r($listReports, true));
        return response()->json([
            "data" => $listReports,
        ]);

    }

    public function detailReport($id) {
        $show = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('result detail : '. print_r($show, true));
        if(!empty($show)) {
            return response()->json([
                  BaseController::STATUS=>BaseController::HTTP_BAD_REQUEST,
                  BaseController::MESSAGE=>'error',
                  BaseController::DATA=>$show
            ]);
        }
    }


    public function filter(Request $request)
    {
        $data = $request->all();
        Log::channel('reportsksnb')->info('request filter :' . print_r($data, true));
        $search = $this->ksnbRepo->filter($data);
        Log::channel('reportsksnb')->info('search result: ' . print_r($search, true));
        return response()->json([
              BaseController::STATUS=>BaseController::HTTP_OK,
              BaseController::MESSAGE=>BaseController::SUCCESS,
              BaseController::DATA=>$search
        ]);
    }

    //send mail khi ???????c duy???t
    public function updateProcess(Request $request, $id) {
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('request updateProcess :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_ACTIVE ,
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->update_confrim($input, $id);
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE=>BaseController::ERROR,
                BaseController::DATA => [],
            ]);
        }
        $emailAsm = ksnbApi::getUserEmailAsm($report['user_email']);
        $listEmail = [];
        if ($emailAsm['status'] && $emailAsm['status'] == BaseController::HTTP_OK) {
            $listEmail += $emailAsm['data'];
        }
        $listEmail[] = $report['user_email'];
        $listEmail[] = $report['email_tpgd'];
        $listEmail[] = $report['created_by'];
        // $emailKSNB = ksnbApi::getUserEmailKsnb();
        // if ($emailKSNB['status'] && $emailKSNB['status'] == BaseController::HTTP_OK) {
        //     $listEmail += $emailKSNB['data'];
        // }
        $user = $this->userCpanelRepo->findByEmail($report['user_email']);
        $position = $this->roleRepository->checkPosition($user['_id']);
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code" => "report_ksnb_email_confirm",
            "code_error" => $report['code_error'],
            'user_name' => $report['user_name'],
            "user_email" => $listEmail,
            "user_nv"       => $report['user_email'],
            "store_name" => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "urlItem" => $request['urlItem'],
            "description" => $report['description'],
            "description_error"   => $report['description_error'],
            "created_by" =>  $a[0]['full_name'],
            "urlImg" => $request['url'],
            "position" => $position,
        ];
        ksnbApi::sendEmailConfrimReports($sendEmail);
        return response()->json([
                BaseController::STATUS=>BaseController::HTTP_OK,
                BaseController::MESSAGE=>BaseController::SUCCESS,
                BaseController::DATA => $updateProcess,
            ]);
    }

    //l???y h???t user_email theo c???p b???c
    //
    public function getEmailCht(Request $request)
    {
        $email = $request->get("email");
        $listUser = ksnbApi::getUserEmailCht($email);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $listUser,
        ]);


    //tbp ksnb
    }
    public function ksnb_validate_two()
    {
        $result = $this->roleRepository->getEmailKsnb();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result,
        ]);
    }
    public function getEmailAsm(Request $request)
    {
        $listUser = ApiCall::getUserEmailAsm($email);
        if (!empty($listUser) && $listUser["status"] == Response::HTTP_OK) {
            $reports = $this->ksnbRepo->getUserEmailAsm($listUser["data"]);
        }
    }
    // staff kd

    public function ksnb_validate_three($id)
    {
        $result = $this->ksnbRepo->getEmailAll($id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result,
        ]);
    }


    public function all_user_ksnb(Request $request)
    {
        $email = $request->get("email");
        $user = ApiCall::getUserEmail($email);
        if ($user['status'] !== Response::HTTP_OK){
            return NULL;
        }
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $user,
        ]);
    }


    //get list report ksnb

    public function get_list_ksnb(Request $request)
    {
        $email = $request->get("email");
        $listUser = ApiCall::getUserEmail($email);
        $reports = [];
        if (!empty($listUser) && $listUser["status"] == Response::HTTP_OK) {
            $reports = $this->ksnbRepo->get_email_ksnb($listUser["data"]);
        }
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $reports,
        ]);

    }

    //send mail khi ko ???????c duy???t
    public function updateEmailNotConfrim(Request $request, $id) {
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('request updateProcess :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_NOT_ACTIVE ,
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateNotConfrim($input, $id);
        Log::channel('reportsksnb')->info('updateNotConfrim id:' . $id);
        Log::channel('reportsksnb')->info('updateProcess:' . print_r($updateProcess, true));
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::UPDATE_FAIL,
                BaseController::DATA => [],
            ]);
        }
        // $emailKSNB = ksnbApi::getUserEmailKsnb();
        // $listEmail = [];
        // if ($emailKSNB['status'] && $emailKSNB['status'] == BaseController::HTTP_OK) {
        //     $listEmail = $emailKSNB['data'];
        // }
        $user = $this->userCpanelRepo->findByEmail($report['user_email']);
        $position = $this->roleRepository->checkPosition($user['_id']);
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code" => "report_ksnb_email_not_confirm",
            "code_error" => $report['code_error'],
            'user_name' => $report['user_name'],
            "user_email" => [$report['created_by']],
            "user_nv"    => $report['user_email'],
            "store_name" => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "urlItem" => $request['urlItem'],
            "description" => $report['description'],
            "position" => $position,
            "description_error"   => $report['description_error'],
            "created_by" => $a[0]['full_name'],

        ];
        ksnbApi::sendEmailNotConfrimReports($sendEmail);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $updateProcess,
        ]);
    }

    //send emmail Reconfrim g???i duy???t l???i saun khi ko ??c duy???t
    public function updateEmailReConfrim(Request $request, $id) {
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('request updateReConfrim :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COULUMN_PROCESS_RECONFIRM ,
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateReConfrim($input, $id);
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::UPDATE_FAIL,
                BaseController::DATA => [],
            ]);
        }
        $emailTBPKSNB = config('reportsksnb.TBPKSNB');
        $listEmail = [];
        if ($emailTBPKSNB) {
            $listEmail += $emailTBPKSNB;
        }
        //g???i email
        $user = $this->userCpanelRepo->findByEmail($report['user_email']);
        $position = $this->roleRepository->checkPosition($user['_id']);
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code" => "report_ksnb_email_reconfirm",
            "code_error" => $report['code_error'],
            'user_name' => $report['user_name'],
            "user_email" => $listEmail,
            "user_nv"       => $report['user_email'],
            "store_name" => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "urlItem" => $request['urlItem'],
            "description" => $report['description'],
            "position" => $position,
            "description_error"   => $report['description_error'],
            "created_by" => $a[0]['full_name'],

        ];
        ksnbApi::sendEmailReConfrimReports($sendEmail);
        return response()->json([
                BaseController::STATUS => BaseController::HTTP_OK,
                BaseController::MESSAGE => BaseController::SUCCESS,
                BaseController::DATA => $updateProcess,
            ]);
    }

    //send mail v?? update tr???ng th??i bi??n b???n khi ????a ra k???t lu???n
    public function updateInfer(Request $request, $id) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'infer' => 'required',
        ], [
            'infer.required' => 'K???t lu???n kh??ng ???????c ????? tr???ng',
        ]);

        if($validator->fails()) {
            Log::channel('reportsksnb')->info('sendfeedback validator' .$validator->errors()->first());
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE=>$validator->errors()->first(),
                BaseController::DATA => [],
            ]);
        }
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('request updateInfer :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_BLOCK,
            ReportsKsnb::COLUMN_INFER  => $request['infer']
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateInfer($input, $id);
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::UPDATE_FAIL,
                BaseController::DATA => [],
            ]);
        }
        $emailTBPKSNB = config('reportsksnb.TBPKSNB');
        $listEmail = [];
        if ($emailTBPKSNB) {
            $listEmail += $emailTBPKSNB;
        }
        $listEmail[] = $report['created_by'];
        $emailAsm = ksnbApi::getUserEmailAsm($report['user_email']);
        if ($emailAsm['status'] && $emailAsm['status'] == BaseController::HTTP_OK) {
            $listEmail += $emailAsm['data'];
        }
        $listEmail['user_email'] = $report['user_email'];
        $listEmail['email_tpgd'] = $report['email_tpgd'];
        // $emailKSNB = ksnbApi::getUserEmailKsnb();
        // if ($emailKSNB['status'] && $emailKSNB['status'] == BaseController::HTTP_OK) {
        //     $listEmail += $emailKSNB['data'];
        // }
        $user = $this->userCpanelRepo->findByEmail($report['user_email']);
        $position = $this->roleRepository->checkPosition($user['_id']);
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code" => "report_ksnb_email_infer",
            "code_error" => $report['code_error'],
            'user_name' => $report['user_name'],
            "user_email" => $listEmail,
            "user_nv"       => $report['user_email'],
            "store_name" => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "urlItem" => $request['urlItem'],
            "comment" => $report['comment'],
            "infer" => $request['infer'],
            "description" => $report['description'],
            "description_error" => $report['description_error'],
            "created_by" => $a[0]['full_name'],
            "position" => $position,
        ];
        ksnbApi::sendEmailInferReports($sendEmail);
        return response()->json([
                BaseController::STATUS => BaseController::HTTP_OK,
                BaseController::MESSAGE => BaseController::SUCCESS,
                BaseController::DATA => $updateProcess,
            ]);
    }

    //g???i mail khi nv vi ph???m ph???n h???i
    public function sendfeedback(Request $request, $id) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'comment' => 'required',
        ], [
            'comment.required' => 'Ph???n h???i kh??ng ???????c ????? tr???ng',
        ]);

        if($validator->fails()) {
            Log::channel('reportsksnb')->info('sendfeedback validator' .$validator->errors()->first());
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE=>$validator->errors()->first(),
                BaseController::DATA => [],
            ]);
        }
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('sendfeedback Item :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_FEEDBACK ,
            ReportsKsnb::COLUMN_COMMENT  => $data['comment'],
            ReportsKsnb::COLUMN_CREATED_BY => $data['created_by']
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateFeedBack($input, $id);
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::UPDATE_FAIL,
                BaseController::DATA => [],
            ]);
        }
        // $emailAsm = ksnbApi::getUserEmailAsm($report['user_email']);
        // $listEmail = [];
        // if ($emailAsm['status'] && $emailAsm['status'] == BaseController::HTTP_OK) {
        //     $listEmail += $emailAsm['data'];
        // }
        // $listEmail[] = $report['user_email'];
        // $listEmail[] = $report['email_tpgd'];
        // $emailKSNB = ksnbApi::getUserEmailKsnb();
        // if ($emailKSNB['status'] && $emailKSNB['status'] == BaseController::HTTP_OK) {
        //     $listEmail += $emailKSNB['data'];
        // }
        $emailTBPKSNB = config('reportsksnb.TBPKSNB');
        $listEmail = [];
        if ($emailTBPKSNB) {
            $listEmail += $emailTBPKSNB;
        }
        $listEmail[] = $report['created_by'];
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code" => "report_ksnb_email_user_feedback",
            "code_error" => $report['code_error'],
            'user_name' => $report['user_name'],
            "user_email" => $listEmail,
            "user_nv"       => $report['user_email'],
            "store_name" => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "comment"   => $request['comment'],
            "urlItem" => $data['urlItem'],
            // "description" => $report['description'],
            "created_by" => $a[0]['full_name'],

        ];
        ksnbApi::sendEmailFeedBackReports($sendEmail);
        return response()->json([
                BaseController::STATUS => BaseController::HTTP_OK,
                BaseController::MESSAGE => BaseController::SUCCESS,
                BaseController::DATA => $updateProcess,
            ]);
    }

    public function getEmployeesByStoreId(Request $request)
    {
        $storeId = $request->get("store_id");
        $result  = $this->roleRepository->getEmailGroupNvkd($storeId);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $list,
        ]);
    }


    public function getEmailCHTByStoreId(Request $request)
    {
         $storeId = $request->get("store_id");
        $emailCHT = [];
        $employeeHO = $this->roleRepository->getMailByRole($storeId);
        if ($employeeHO) {
            foreach ($employeeHO as $email) {
                $user = $this->userCpanelRepo->findByEmail($email);
                $isCHT = $this->roleRepository->isCHT($user['_id']);
                $isTPHO = $this->roleRepository->isTPHO($user['_id']);
                if ($isCHT) {
                    $emailCHT[] = $email;
                } elseif ($isTPHO) {
                    $emailCHT[] = $email;
                }
            }
        }
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $emailCHT,
        ]);
    }

    //send mail khi nh???n g???i duy???t bb sau khi t???o bb
    public function getEmailWaitConfrim(Request $request, $id)
    {
        $requestData = $request->all();
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('request sendfeedback :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_NEW,
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateWaitConfrim($input, $id);
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE=>BaseController::ERROR,
                BaseController::DATA => [],
            ]);
        }

        $emailTBPKSNB = config('reportsksnb.TBPKSNB');
        $listEmail = [];
        if ($emailTBPKSNB) {
            $listEmail += $emailTBPKSNB;
        }
        $user = $this->userCpanelRepo->findByEmail($report['user_email']);
        $position = $this->roleRepository->checkPosition($user['_id']);
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code"          => "report_ksnb_email_wait_confirm",
            "code_error"    => $report['code_error'],
            'user_name'     => $report['user_name'],
            "user_email"    => $listEmail,
            "user_nv"       => $report['user_email'],
            "position"      => $position,
            "store_name"    => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "urlItem"       => $requestData['urlItem'],
            "description"   => $report['description'],
            "description_error"   => $report['description_error'],
            "created_by" => $a[0]['full_name'],
        ];
        ksnbApi::sendEmailWaitConfrimReports($sendEmail);
        return response()->json([
            BaseController::STATUS => Response::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $updateProcess,
        ]);
    }

    //mail nh??n vi??n c???a c??c ph??ng ban
    public function allMailRoll(Request $request)
    {
        $id = $request->input('id_room');
        $result = $this->roleRepository->getMailByRole($id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }


    //c??c ph??ng ban
    public function getAllRoom()
    {
        $result = $this->roleRepository->getAllRoom();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    public function getByEmailCaptionHo()
    {
        $result = $this->roleRepository->getByEmailCaptionHo();
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }


    public function cancelRpNv($id)
    {
        $result = $this->ksnbRepo->cancelReportnv($id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    public function cancelRpTbp($id)
    {
        $result = $this->ksnbRepo->cancelReporttbp($id);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $result
        ]);
    }

    //ph???n h???i l???i c???a ksnb cho ng?????i vi ph???m
    public function ksnbFeedback(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'ksnb_comment' => 'required',
        ], [
            'ksnb_comment.required' => 'Ph???n h???i kh??ng ???????c ????? tr???ng',
        ]);

        if($validator->fails()) {
            Log::channel('reportsksnb')->info('ksnbFeedback validator' .$validator->errors()->first());
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE=>$validator->errors()->first(),
                BaseController::DATA => [],
            ]);
        }
        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('Item ksnbfeedback :'  . print_r($data, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_WAIT_FEEDBACK ,
            ReportsKsnb::COLUMN_KSNB_COMMENT  => $data['ksnb_comment'],
            ReportsKsnb::COLUMN_CREATED_BY => $data['created_by']
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateKsnbFeedback($input, $id);
        Log::channel('reportsksnb')->info('update:' . print_r($updateProcess, true));
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::UPDATE_FAIL,
                BaseController::DATA => [],
            ]);
        }
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code" => "report_ksnb_email_user_feedback",
            "code_error" => $report['code_error'],
            'user_name' => $report['user_name'],
            "user_email" => [$report['user_email']],
            "user_nv"       => $report['user_email'],
            "store_name" => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            // "comment"   => $report['comment'],
            "urlItem" => $data['urlItem'],
            "description" => $report['description'],
            "created_by" => $a[0]['full_name'],
            "ksnb_comment" => $data['ksnb_comment'],

        ];
        ksnbApi::sendEmaiKsnbFeedback($sendEmail);
        return response()->json([
            BaseController::STATUS => BaseController::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $updateProcess,
        ]);
    }


    public function waitInfer(Request $request, $id)

    {
        $requestData = $request->all();

        $report = $this->ksnbRepo->find($id);
        Log::channel('reportsksnb')->info('request waitInfer :'  . print_r($report, true));
        $input = [
            ReportsKsnb::COLUMN_PROCESS  => ReportsKsnb::COLUMN_PROCESS_WAIT_INFER ,
        ];
        Log::channel('reportsksnb')->info('input:' . print_r($input, true));
        $updateProcess = $this->ksnbRepo->updateWaitInfer($input, $id);
        if (!$updateProcess) {
            return response()->json([
                BaseController::STATUS => Response::HTTP_BAD_REQUEST,
                BaseController::MESSAGE => BaseController::UPDATE_FAIL,
                BaseController::DATA => [],
            ]);
        }
        //sendEmail
        $emailTBPKSNB = config('reportsksnb.TBPKSNB');
        $listEmail = [];
        if ($emailTBPKSNB) {
            $listEmail += $emailTBPKSNB;
        }
        $user = $this->userCpanelRepo->findByEmail($report['user_email']);
        $position = $this->roleRepository->checkPosition($user['_id']);
        $a = $this->userCpanelRepo->getUserNameByEmail($report['created_by']);
        $sendEmail = [
            "code"          => "report_ksnb_email_wait_infer",
            "code_error"    => $report['code_error'],
            'user_name'     => $report['user_name'],
            "user_email"    => $listEmail,
            "user_nv"       => $report['user_email'],
            "position"      => $position,
            "store_name"    => $report['store_name'],
            "type"       => KsnbCodeError::getTypeName($report['type']),
            "punishment" => KsnbCodeError::getPunishmentName($report['punishment']),
            "discipline" => KsnbCodeError::getDisciplineName($report['discipline']),
            "urlItem"       => $requestData['urlItem'],
            "description"   => $report['description'],
            "description_error"   => $report['description_error'],
            "created_by" => $a[0]['full_name'],
        ];
        ksnbApi::sendEmailWaitInferReports($sendEmail);
        return response()->json([
                BaseController::STATUS => BaseController::HTTP_OK,
                BaseController::MESSAGE => BaseController::SUCCESS,
                BaseController::DATA => $updateProcess,
            ]);
    }

//c???p nh???t ti???n tr??nh khi bi??n b???n kh??ng ph???n h???i sau ba ng??y
    public function endTime()
    {
        $report = $this->ksnbRepo->endTimeRp();
        foreach ($report as $key => $value){
        $a = $this->userCpanelRepo->getUserNameByEmail($value['created_by']);
        $urlItem = env('LMS_TN_PATH') . '?target_url=' . route('ViewCpanel::ReportKsnb.detailReport', ['id' =>$value['_id']]);
        $sendEmail = [
            "code"          => "report_ksnb_email_endtime",
            "code_error"    => $value['code_error'],
            'user_name'     => $value['user_name'],
            "user_email"    => $value['user_email'],
            "user_nv"       => $value['created_by'],
            "store_name"    => $value['store_name'],
            "type"          => KsnbCodeError::getTypeName($value['type']),
            "punishment"    => KsnbCodeError::getPunishmentName($value['punishment']),
            "discipline"    => KsnbCodeError::getDisciplineName($value['discipline']),
            "description"   => $value['description'],
            "description_error"   => $value['description_error'],
            "created_by" => $a[0]['full_name'],
            "comment" => 'Ng?????i vi ph???m kh??ng ph???n h???i',
            "urlItem" => $urlItem,
        ];
         ksnbApi::sendMailEndTime($sendEmail);
         Log::channel('reportsksnb')->info('sendEndTime :'  . print_r($value['created_by'], true));
         $this->ksnbRepo->updateEndTime($value['_id']);
        }


        return response()->json([
            BaseController::STATUS => Response::HTTP_OK,
            BaseController::MESSAGE => BaseController::SUCCESS,
            BaseController::DATA => $report,
        ]);
    }


}
