<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
//use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Http\Request;
use App\Models\Feedback;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
/*    public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       // $settings = Setting::first(); // moved to AppServiceProvider

        $slides = Slide::where('status', 1)
            ->orderBy('position', 'asc')->take(3)
            ->get();

        // dd($slides);
        // dd($products);
       // dd($categories);
        return view('frontend.pages.home', compact('slides'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'subject' => 'nullable|string|max:255',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('success', 'Your message has been submitted successfully.');
    }

    public function feedbackSubmit(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'reason' => 'nullable|string|max:255',
            'feedback_message' => 'required|string',
            'overall_rating' => 'required|integer|min:1|max:5',
        ]);

        Feedback::create($validated);

        // Send email
        \Mail::send('emails.feedback', ['data' => $validated], function ($message) use ($validated) {
            $message->to('info@mrbakerbd.com')
                ->subject('New Customer Feedback');
        });

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

}
