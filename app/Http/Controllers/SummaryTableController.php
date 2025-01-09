<?php

namespace App\Http\Controllers;

use App\Models\Customer;  // Import your model
use Illuminate\Http\Request;

class SummaryTableController extends Controller
{
    public function index()
    {
        $breadcrumb = 'TIP Summary';

        // Replace this with your actual data fetching logic
        $commercialQualityData = [
            [
                'name' => 'Junaid Ahmed',
                'company' => 'Product A',
                'phone' => 023244444,
                'email' => 'xyz@gmail.com',
                'country' => 'Completed',
                'status' => 'true'
            ],
            [
                'name' => 'John Doe',
                'company' => 'Product A',
                'phone' => 023244444,
                'email' => 'xyz@gmail.com',
                'country' => 'Completed',
                'status' => 'false'
            ],
            [
                'name' => 'John Doe',
                'company' => 'Product A',
                'phone' => 023244444,
                'email' => 'xyz@gmail.com',
                'country' => 'Completed',
                'status' => 'true'
            ],
            [
                'name' => 'Waseem Akram',
                'company' => 'Product A',
                'phone' => 023244444,
                'email' => 'xyz@gmail.com',
                'country' => 'Completed',
                'status' => 'true'
            ],
            [
                'name' => 'Razaq',
                'company' => 'Product A',
                'phone' => 023244444,
                'email' => 'abc@gmail.com',
                'country' => 'Completed',
                'status' => 'true'
            ],
            [
                'name' => 'Kamran',
                'company' => 'Product A',
                'phone' => 3244444,
                'email' => 'xyz@gmail.com',
                'country' => 'Completed',
                'status' => 'false'
            ],
            [
                'name' => 'Ali',
                'company' => 'Product A',
                'phone' => 4542334,
                'email' => 'abc@gmail.com',
                'country' => 'UK',
                'status' => 'false'
            ],


        ];  // Example: fetch all commercialQualityData

        // Check if there is no data and pass a message accordingly
        $dataMessage = collect($commercialQualityData)->isEmpty() ? "No data available to display." : null;

        return view('tip-summary', compact('breadcrumb', 'commercialQualityData', 'dataMessage'));
    }
}