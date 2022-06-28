<?php


namespace Modules\ViewCpanel\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Modules\MongodbCore\Repositories\KsnbCodeErrorsRepository;

class KsnbCodeErrorsController extends BaseController
{
    private $ksnbCodeErrorsRepository;

    public function __construct(KsnbCodeErrorsRepository $ksnbCodeErrorsRepository)
    {
        $this->ksnbCodeErrorsRepository = $ksnbCodeErrorsRepository;
    }

    public function list()
    {
        $list = $this->ksnbCodeErrorsRepository->getAllKsnbErrors();
        $data = [];
        $data['list'] = $list;
        $updateStatusUrl = route("viewcpanel::ksnbErrors.updateStatus");
        $data['updateStatusUrl'] = $updateStatusUrl;
        return view('viewcpanel::ksnbErrors.list',$data);
    }

    public function create(Request $request)
    {
        $create = $this->ksnbCodeErrorsRepository->getAllKsnbErrors();
        $data['create'] = $create;
        return view('viewcpanel::ksnbErrors.create',$data);
    }

    public function detail($id)
    {
        $detail = $this->ksnbCodeErrorsRepository->find($id);
        $data = [];
        $data['detail'] =$detail;
        if (!$detail){
            abort(404);
        }
        return view('viewcpanel::ksnbErrors.detail',$data);
    }

    public function update(Request $request,$id)
    {
        $user = session('user');
        $data = [];

        $dataPost = $request->all();

        if (isset($dataPost['code_error'])) {
            $findExists = $this->ksnbCodeErrorsRepository->checkExistCodeError($id, ucfirst($dataPost['code_error']));
            if ($findExists) {
                session()->flash('error', 'TRÙNG MÃ LỖI');
                return redirect()->route('viewcpanel::ksnbErrors.edit',$id)->with('error','TRÙNG MÃ LỖI');
            }
            $request->merge([
                'code_error' => ucfirst($dataPost['code_error'])
            ]);
        }
        $validate = $request->validate([
            'code_error' => 'required|unique:mongodb.code_errors,_id,'.$id,
            'description' => 'required',
            'type' => 'required',
            'punishment' => 'required',
            'discipline' => 'required',
            'description' => 'required',
        ],
            [
                "code_error.required" => "Mã lỗi không được để trống!",
                "code_error.unique" => "Mã lỗi đã tồn tại",
                "code_error.min" => "Mã lỗi ít nhất hai kí tự",
                "code_error.max" => "Mã lỗi nhiều nhất tám kí tự",
                "description.required" => "Tên sản phẩm đã tồn tại!",
                "type.required" => "Nhóm vi phạm không được để trống!",
                "punishment.required" => "Chế tài phạt không được để trống!",
                "discipline.required" => "Hình thức kỷ luật không được để trống!",
                "description.required" => "Mô tả không được để trống!",
            ]

        );
        $dataPost['update_by'] = $user['email'];
        $update = $this->ksnbCodeErrorsRepository->updateKsnbErrors($dataPost,$id);
        $detail = $this->ksnbCodeErrorsRepository->find($id);
        $data['detail'] = $detail;
        session()->flash('success');
        return redirect()->route('viewcpanel::ksnbErrors.list',$data)->with('status','CẬP NHẬT MÃ LỖI THÀNH CÔNG');
    }

    public function save(Request $request)
    {
        $user = session('user');
        $dataPost = $request->all();
        if (isset($dataPost['code_error'])) {
            $request->merge([
                'code_error' => ucfirst($dataPost['code_error'])
            ]);
        }
        $validate = $request->validate([
            'code_error' => 'required|unique:mongodb.code_errors|min:2|max:8',
            'description' => 'required',
            'type' => 'required',
            'punishment' => 'required',
            'discipline' => 'required',
        ],
            [
                "code_error.required" => "Mã lỗi không được để trống!",
                "code_error.unique" => "Mã lỗi đã tồn tại",
                "code_error.min" => "Mã lỗi ít nhất ba kí tự",
                "code_error.max" => "Mã lỗi nhiều nhất tám kí tự",
                "description.required" => "Tên sản phẩm đã tồn tại!",
                "type.required" => "Nhóm vi phạm không được để trống!",
                "punishment.required" => "Chế tài phạt không được để trống!",
                "discipline.required" => "Hình thức kỷ luật không được để trống!",
                "description.required" => "Mô tả không được để trống!",
            ]
        );
        $dataPost['create_by'] = $user["email"];
        $dataPost = $this->ksnbCodeErrorsRepository->createKsnbErrors($dataPost);
        session()->flash('success', 'Thêm thành công');
        return redirect()->route('viewcpanel::ksnbErrors.list')->with('status','THÊM MÃ LỖI THÀNH CÔNG');

    }

    public function edit($id)
    {
        $detail = $this->ksnbCodeErrorsRepository->find($id);
        $data = [];
        $data['detail'] = $detail;
        return view('viewcpanel::ksnbErrors.update',$data);
    }

    public function status(Request $request)
    {
        $id = $request->input("id");
        $status = $this->ksnbCodeErrorsRepository->update_status($id);
        $response = [
            'status' => Response::HTTP_OK,
        ];
        return response()->json($response);
    }


}
