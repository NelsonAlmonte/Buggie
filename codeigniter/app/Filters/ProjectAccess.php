<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ProjectAccess implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = service('uri');
        $collaboratorProjects = [];
        $collaboratorProjects = [];

        $projectToBeAccessed = $uri->getSegment(2);

        $collaboratorProjects = session()->get('projects');
        $collaboratorProjects = array_map(fn ($project) => $project['slug'], $collaboratorProjects);

        $loggedUserPermissions = session()->get('auth')['permissions'];
        if (in_array('project', $loggedUserPermissions)) return;

        if (!in_array($projectToBeAccessed, $collaboratorProjects)) {
            session()->setFlashdata([
              'message' => MESSAGE_ERROR, 
              'color' => MESSAGE_ERROR_COLOR, 
              'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('project');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
