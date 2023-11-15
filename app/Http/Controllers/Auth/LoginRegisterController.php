<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;


class LoginRegisterController extends Controller
{
    public function register(){
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photo' =>'image|nullable|max:1999'
        ]);

        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan);
        }  
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $path
        ]);

        $content = [
            'name' => 'Berhasil Registrasi',
            'subject' => ('To : '. $request->email),
            'nama' => ('Name :'. $request->name),
            'email' => ('Email :'. $request->email),
        ];

        Mail::to($request->email)->send(new SendEmail($content));

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('dashboard')
            ->withSuccess('You have successfully registered & logged in!');
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }

    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::check()) {
            return view('auth.dashboard');
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }

    public function editFoto(){
        if (Auth::check()) {
            $data = User::where('email', Auth::user()->email)->first()->photo;
            return view('auth.edit', compact('data'));
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    public function updateFoto(Request $request){
        if (Auth::check()) {
            $request->validate([
                'photo' =>'image|nullable|max:1999'
            ]);
            if ($request->hasFile('photo')) {
                $filenameWithExt = $request->file('photo')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('photo')->getClientOriginalExtension();
                $filenameSimpan = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('photo')->storeAs('photos', $filenameSimpan);
            } 
            User::where('email', Auth::user()->email)->update([
                'photo' => $path,
            ]);
            return redirect()->route('dashboard')
                ->withSuccess('You have successfully edit!');
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    public function resizeFoto(){
        if (Auth::check()) {
            $path = User::where('email', Auth::user()->email);
            $data = $path->first()->photo;
            return view('auth.resize', compact('data'));
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    public function updateSizeFoto(){
        if (Auth::check()) {
            $path = User::where('email', Auth::user()->email)->first()->photo;
            $photoPath = public_path('storage/'.$path);
            $thumbnailPath = public_path('storage/thumbnails/'.$path);
            $photoResized = Image::make($photoPath);
            $photoResized->fit(100,100);
            $photoResized->save($thumbnailPath);
            return redirect()->route('dashboard')->with(['message'=> 'Berhasil di resize']); 
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }
}
