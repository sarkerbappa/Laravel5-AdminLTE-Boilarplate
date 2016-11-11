<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use DB;
use Auth;
use Hash;
use App\Model\BookManager;
use App\Model\IssueManager;


class UsersController extends Controller {

  /**
   * Admin login Form
   * @return type
   */
    public function adminLoginForm() {
        return View::make('admin.admin_login')->with('title', 'Login Form');
    }
    /**
     * Logout
     * @return type
     */
     public function getLogOut() {
        Auth::logout();
        return redirect('/');
    }
    /**
     * User login check
     * @return type
     */
    public function adminLoginCheck() {
        $validation_rule = [
            'username' => array('required', 'min:3', 'max:50'),
            'password' => array('required', 'min:6', 'max:50')
        ];
        $validation = Validator::make(Input::all(), $validation_rule);
        // Validation Check
        if ($validation->fails()) {
            return redirect()->back()
                             ->withInput()
                             ->withErrors($validation);
        } // After Validation Authentication start
        else {
            $athentication = Auth::attempt(array('username' => Input :: get('username'), 'password' => Input :: get('password'),'status'=>1));
            if ($athentication) {
                $rememberme = Input::get('remember');
                if (!empty($rememberme)) {
                    //Remember Login data
                    Auth::loginUsingId(Auth::user()->id, true);
                }
                //Redrict to the Target page
                return Redirect::intended('adminDashboard');
            } else {
                //Redrict Login Form with Authentication error massege.
                return Redirect::to('/')->withInput()->with('authentication_error', 'Invalid username/Password!');
            }
        }
    }
    
    /**
     * Update User Profile Form
     */
    public function updateUserProfileForm(){
        $data = array(
            'title' => 'Update User Profile',
        );
        return View::make('admin.pages.users.update_user_profile_form')->with($data);
    }

    
    //Submit Update User Data into database
    public function updateUserProfile() {
        $validation_rule = array(
            'f_name' => array('required', 'max:50'),
            'l_name'  => array('required', 'max:50'),
            'email'      => array('required', 'email', 'max:32'),
            'password'   => array('min:6', 'max:64'),
        );
        $validation = Validator::make(Input::all(), $validation_rule);
        if ($validation->fails()) {
            // if validation failed then returned to the update form with error massege
            return Redirect::to('/updateUserProfileForm')->withErrors($validation);
        } else { 
                 //When validation Passed 
                 //Start Image validation 
                 if(Input::hasFile('image')){
                        $input = array('image' => Input::file('image'));
                        $rules = array(
                            'image' => 'image'
                         );
                        $validator = Validator::make($input, $rules);
                             if ($validator->fails()){
                                 return Redirect::to('/updateUserProfileForm')->with('image_validation_error', 'Image file is not valid !');
                                }else{
                                    //Upload the image and get the image file name. 
                                    $image = Input::file('image');
	                            $destinationPath = 'public/uploads/profile';
                                    $filename = $image->getClientOriginalName();
                                    Input::file('image')->move($destinationPath, $filename);
                                }
                }else {
                    // Get the old image name
                    $filename = Auth::user()->image;
                    
                } //End of the Image validation else
     // Input Form Data into database
                
                
                if (Input :: get('password') != '') {
                DB::table('users')
                        ->where('id', Auth::user()->id)
                        ->update([
                            'f_name' => Input :: get('f_name'),
                            'l_name' => Input :: get('l_name'),
                            'email' => Input :: get('email'),
                            'password' => Hash :: make(Input :: get('password')),
                            'image' => $filename,
                ]);
                }  else {
                     DB::table('users')
                        ->where('id', Auth::user()->id)
                        ->update([
                            'f_name' => Input :: get('f_name'),
                            'l_name' => Input :: get('l_name'),
                            'email' => Input :: get('email'),
                            'password' => Hash :: make(Input :: get('password')),
                            'image' => $filename,
                ]);
                }
            //Redrict to the update form with success massege.  
   return Redirect::to('/updateUserProfileForm')->with('update_success_massege', 'Profile Updated Successfully.');
    }
  }
    
    
    
    
    
    /**
     * Update User Profile.
     */
//    public function updateUserProfile(){
//        $validation_rule = array(
//            'email'         => array('required'),
//            'password'      => array(''),
//        );
//        $validation = Validator::make(Input::all(), $validation_rule);
//        if ($validation->fails()) {
//            return Redirect::to('/updateUserProfileForm')->withInput()->withErrors($validation);
//        } else {
//             if (Input :: get('password') !== '') {
//                DB::table('users')
//                        ->where('id', Auth::user()->id)
//                        ->update([
//                                   'email'    => Input :: get('email'),
//                                   'password' => Hash :: make(Input :: get('password')),
//                                 ]);
//                }  else {
//                     DB::table('users')
//                        ->where('id', Auth::user()->id)
//                        ->update([
//                                   'email' => Input :: get('email'),
//                                 ]);
//                }
//            return Redirect::to('/updateUserProfileForm')->with('update_success_massege', 'Profile Updated Successfully.');
//        }
//    }

    
    /**
     * Admin Dashoard
     * @return type
     */
    public function  adminDashboard(){
         $data = array(
            'title'           => 'Admin Dashboard',
        );
        return View::make('admin.pages.dashboard.admin_dashboard')->with($data);
    }
 
    

}

