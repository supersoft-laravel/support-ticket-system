<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view company');
        try {
            $companies = Company::orderBy('created_at', 'desc')
            ->get();
            return view('dashboard.company.index', compact('companies'));
        } catch (\Throwable $th) {
            Log::error("Company Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create company');
        try {
            return view('dashboard.company.create');
        } catch (\Throwable $th) {
            Log::error("Company Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create company');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            $company = new Company();
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            do {
                $token = Str::upper(Str::random(14)); // A–Z + 0–9
            } while (Company::where('token', $token)->exists());

            $company->token = $token;
            $company->save();

            return redirect()->route('dashboard.companies.index')->with('success', 'Company created successfully!');
        } catch (\Throwable $th) {
            Log::error("Company Store Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view company');
        try {
            $company = Company::findOrFail($id);
            return view('dashboard.company.show', compact('company'));
        } catch (\Throwable $th) {
            Log::error("Company Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update company');
        try {
            $company = Company::findOrFail($id);
            return view('dashboard.company.edit', compact('company'));
        } catch (\Throwable $th) {
            Log::error("Company Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update company');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            $company = Company::findOrFail($id);
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->save();

            return redirect()->route('dashboard.companies.index')->with('success', 'Company updated successfully!');
        } catch (\Throwable $th) {
            Log::error("Company Update Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete company');
        try {
            $company = Company::findOrFail($id);
            $company->delete();
            return redirect()->route('dashboard.companies.index')->with('success', 'Company deleted successfully!');
        } catch (\Throwable $th) {
            Log::error("Company Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update company');
        try {
            $company = Company::findOrFail($id);
            $message = $company->is_active == 'active' ? 'Company Deactivated Successfully' : 'Company Activated Successfully';
            if ($company->is_active == 'active') {
                $company->is_active = 'inactive';
                $company->save();
            } else {
                $company->is_active = 'active';
                $company->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Company Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
