<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use App\Services\Client\ClientService;
use App\Services\Project\ProjectService;
use App\Services\Task\TaskService;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardRepository $dashboardRepo;
    private ClientService $clientService;
    private TaskService $taskService;
    private ProjectService $projectService;

    public function __construct(DashboardRepository $dashboardRepo,
                                ClientService $clientService,
                                TaskService $taskService,
                                ProjectService $projectService
    )
    {
        $this->dashboardRepo = $dashboardRepo;
        $this->clientService = $clientService;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        
        try {
            $projectSelect = ['id','name','start_date','deadline','status','priority'];
            $withProject = [
                'projectLeaders.user:id,name,avatar',
                'tasks:id,project_id',
                'completedTask:id,project_id'
            ];
            $companyId = AppHelper::getAuthUserCompanyId();
            if (!$companyId) {
                throw new Exception('Company Detail Not Found');
            }
            $date = AppHelper::yearDetailToFilterData();
            //dd($date);
            $dashboardDetail = $this->dashboardRepo->getCompanyDashboardDetail($companyId, $date);
            $dashboardHolidayThisMonth = $this->dashboardRepo->getHolidayThisMonth($companyId);
            $topClients = $this->clientService->getTopClientsOfCompany();
            $taskPieChartData = $this->taskService->getTaskDataForPieChart();
            $projectCardDetail = $this->projectService->getProjectCardData();
            $recentProjects = $this->projectService->getRecentProjectListsForDashboard($projectSelect,$withProject);
            //dd($dashboardTotalHolidayThisMonth);
            return view('admin.dashboard', compact(
                'dashboardDetail',
                'dashboardHolidayThisMonth',
                'topClients',
                'taskPieChartData',
                'projectCardDetail',
                'recentProjects'
                )
            );
        } catch (Exception $exception) {
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage());
        }
    }


}
