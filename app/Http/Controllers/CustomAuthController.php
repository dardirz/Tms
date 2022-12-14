<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/')
                        ->withSuccess('Signed in');
        }

        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function registration()
    {

        return view('auth.registration');

    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("/")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function dashboard()
    {

            return view('dashboard');


        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function home(){

            return view('home');
        
        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

    public function showUsers(){
        $user = User::all();
        return view('admin.users',['user'=>$user]);
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('admin.edit',['user'=>$user]);
    }

    public function update(Request $request,$id){

        $validator=$request->validate([
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if($validator){

            $user = User::findOrFail($id);
            $user->update($request->all());
            return redirect(route('user-show'))->with('sucess','updated');
        }else{
            return back()->with('fail','some error');
        }


    }

    public function destroy( $id)
    {
        //
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(route('user-show'))->with('sucess', 'User is Deleted Successful!');
    }



}
