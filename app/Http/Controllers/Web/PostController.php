<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\BranchRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\PostRepository;
use App\Requests\Post\PostRequest;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    private $view = 'admin.post.';

    private PostRepository $postRepo;
    private DepartmentRepository $departmentRepo;
    private CompanyRepository $companyRepo;

    public function __construct(PostRepository $postRepo, DepartmentRepository $departmentRepo, CompanyRepository $companyRepo,)
    {
        $this->postRepo = $postRepo;
        $this->companyRepo = $companyRepo;
        $this->departmentRepo = $departmentRepo;
    }


    public function index(Request $request)
    {
        $this->authorize('list_post');
        try {
            $filterParameters = [
                'name' =>  $request->name ?? null,
                'department' => $request->department ?? null,
                'branch' => $request->branch_id ?? null
            ];
            $postSelect = ['*'];
            $with = ['department:id,dept_name','employees:id,name,post_id,avatar'];
            $departments = $this->departmentRepo->pluckAllDepartments();

            $select_branch = ['id','name'];
            $with_branch = ['branches:id,name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select_branch,$with_branch);

            $posts = $this->postRepo->getAllDepartmentPosts($filterParameters,$with,$postSelect);
           // dd($posts);
            return view($this->view . 'index', compact('posts',
                'filterParameters',
                'companyDetail',
                'departments'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_post');
        try {
            $with = [];
            $select = ['id', 'dept_name'];
            $select_branch = ['id','name'];
            $with_branch = ['branches:id,name'];
            $departmentDetail = $this->departmentRepo->getAllActiveDepartments($with, $select);
            $companyDetail = $this->companyRepo->getCompanyDetail($select_branch,$with_branch);
            
            return view($this->view . 'create', compact('departmentDetail','companyDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getAllPostsByBranchId($deptId)
    {
        try {
            $with = [];
            $select = ['post_name', 'id'];
            $posts = $this->postRepo->getAllActivePostsByDepartmentId($deptId,$with,$select);
            return response()->json([
                'data' => $posts
            ]);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(),$exception->getCode());;
        }
    }

    public function store(PostRequest $request)
    {
        $this->authorize('create_post');
        try {
          
            $validatedData = $request->validated();
            //dd($validatedData);
            DB::beginTransaction();
            $this->postRepo->store($validatedData);
            DB::commit();
            return redirect()->route('admin.posts.index')->with('success', 'New Post Added Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $this->authorize('edit_post');
        try{
            $postDetail = $this->postRepo->getPostById($id);
            $with = [];
            $select = ['id', 'dept_name'];
            $departmentDetail = $this->departmentRepo->getAllActiveDepartments($with, $select);
            $select_branch = ['id','name'];
            $with_branch = ['branches:id,name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select_branch,$with_branch);

            return view($this->view.'edit',
                compact('postDetail','departmentDetail','companyDetail')
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(PostRequest $request, $id)
    {
        $this->authorize('edit_post');
        try{
            $validatedData = $request->validated();
            $postDetail = $this->postRepo->getPostById($id);
            if(!$postDetail){
                throw new \Exception('Post Detail Not Found',404);
            }
            DB::beginTransaction();
            $post = $this->postRepo->update($postDetail,$validatedData);
            DB::commit();
            return redirect()->route('admin.posts.index')->with('success', 'Post Detail Updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())
                ->withInput();
        }

    }

    public function toggleStatus($id)
    {
        $this->authorize('edit_post');
        try {
            DB::beginTransaction();
            $this->postRepo->toggleStatus($id);
            DB::commit();
            return redirect()->back()->with('success', 'Status changed  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_post');
        try {
            $postDetail = $this->postRepo->getPostById($id);
            if (!$postDetail) {
                throw new \Exception('Post Detail Not Found', 404);
            }
            DB::beginTransaction();
                $this->postRepo->delete($postDetail);
            DB::commit();
            return redirect()->back()->with('success', 'Post Detail Deleted  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
