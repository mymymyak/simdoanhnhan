<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\AdminUsersEditFormRequest;
use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\RegistrationFormRequest;
use Illuminate\Http\Request;
use Sentinel;
use App\Repositories\Domain\DomainInterface;

class AdminUsersController extends Controller
{
    /**
     * @var $user
     */
    protected $user;
    protected $domain;


    public function __construct(UserRepositoryInterface $user, DomainInterface $domain)
    {
        $this->user = $user;
        $this->domain = $domain;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
    	$user = Sentinel::getUser();
        $users = $this->user->getAllByDomain($user->domain);
        $admin = Sentinel::findRoleByName('Admins');
        return view('protected.admin.users.index.list_users')->withUsers($users)->withAdmin($admin);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $roles = Sentinel::getRoleRepository()->all();
        $domains = $this->domain->getDomainActive();
        $array_domains = [];
        foreach ($domains as $domain) {
            $array_domains[$domain->domain] = $domain->domain;
        }
        $array_roles = [];
        foreach ($roles as $role) {
            $array_roles[$role->id] = $role->name;
        }

        return view('protected.admin.users.index.create', ['roles' => $array_roles, 'domains' => $array_domains]);
    }

    public function store(RegistrationFormRequest $request){
        $input = $request->only('email', 'password', 'first_name', 'last_name', 'domain');
        $user = Sentinel::registerAndActivate($input);
        $user->domain = $request->get('domain');
        $user->save();
        // Find the role using the role name
        $usersRole = Sentinel::findRoleByName('Users');

        // Assign the role to the users
        $usersRole->users()->attach($user);
        return redirect('admin/profiles')->withFlashMessage('User Successfully Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);
        $user_role = $user->roles->first()->name;

        return view('protected.admin.users.index.show_user', ['user' => $user, 'user_role' => $user_role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $domains = $this->domain->getDomainActive();
        $array_domains = [];
        foreach ($domains as $domain) {
            $array_domains[$domain->domain] = $domain->domain;
        }

        $user = $this->user->find($id);
        $roles = Sentinel::getRoleRepository()->all();
        $user_role = $user->roles()->count() > 0 ? $user->roles()->first()->id : null;
		$array_roles = [];
        foreach ($roles as $role) {
            $array_roles[$role->id] = $role->name;
        }

        return view('protected.admin.users.index.edit_user', [
            'user' => $user,
            'roles' => $array_roles,
            'user_role' =>$user_role,
            'domains' => $array_domains
            ]);
    }

	/**
	 * @param                           $id
	 * @param AdminUsersEditFormRequest $request
	 *
	 * @return mixed
	 * @throws \Illuminate\Validation\ValidationException
	 */
    public function update($id, AdminUsersEditFormRequest $request) {
	    $user = $this->user->find($id);
	    if (!$request->has("password") || strlen($request->post("password")) <= 0) {
		    $input = $request->only('email', 'first_name', 'last_name');
		    $user->fill($input)->save();
		    $this->user->updateRole($id, $request->input('account_type'));
		    return redirect('admin/profiles')->withFlashMessage('User has been updated successfully!');
	    } else {
		    $this->validate($request, [
			    'password' => 'confirmed|min:6',
		    ]);
		    $input = $request->only('email', 'first_name', 'last_name', 'password');
		    $user->fill($input);
		    $user->password = \Hash::make($request->input('password'));
		    $user->save();
		    $this->user->updateRole($id, $request->input('account_type'));
		    return redirect('admin/profiles')->withFlashMessage('User (and password) has been updated successfully!');
	    }
    }
	public function destroy($id)
    {
		$user = $this->user->find($id);
		$niceName = '';
		if (!empty($user)) {
			$niceName = $user->last_name . ' ' . $user->first_name;
			$user->delete();
			return redirect('admin/profiles')->withFlashMessage('Deleted ' . $niceName);
		}
		return redirect('admin/profiles')->withFlashMessage('Deleted error');

	}
}
