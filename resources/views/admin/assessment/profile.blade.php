@extends('layouts.main')
@section('title', 'LGU Profile')

@section('content')

<div class="container bg-white p-5 w-5/6 rounded">
    <div class="mb-4">
        <select name="lgu" class="border rounded p-2 w-full">
            <option>Select LGU</option>
            <option>LGU 1</option>
            <option>LGU 2</option>
            <option>LGU 3</option>
        </select>
    </div>

    <form class="grid gap-6">
        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Respondent Identification</h3>

            <div class="mb-4">
                <label for="respondent" class="block text-sm font-medium">Name of respondent</label>
                <input type="text" id="respondent" class="border rounded w-full p-2">
            </div>

            <div class="mb-4">
                <label for="designation" class="block text-sm font-medium">Designation / Position Title</label>
                <input type="text" id="designation" class="border rounded w-full p-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="positionYears" class="block text-sm font-medium">Number of years in current position</label>
                    <input type="number" id="positionYears" class="border rounded w-full p-2">
                </div>
                <div>
                    <label for="salaryGrade" class="block text-sm font-medium">Salary grade</label>
                    <input type="number" id="salaryGrade" class="border rounded w-full p-2">
                </div>
            </div>
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Fiscal Data (Previous Year)</h3>

            <h5 class="text-md font-medium mb-2">LGU INCOME (2021)</h5>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Total Operating & Service Income</label>
                    <input type="number" class="border rounded w-full p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Approved LSWDO Budget</label>
                    <input type="number" class="border rounded w-full p-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Locally-sourced Revenue</label>
                    <input type="number" class="border rounded w-full p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Total Social Services & Social Welfare Budget</label>
                    <input type="number" class="border rounded w-full p-2">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">External Revenue</label>
                <input type="number" class="border rounded w-full p-2">
            </div>
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">LSWD OFFICE DATA</h3>

            <h5 class="text-md font-medium mb-2">ACCESS TO ICT</h5>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Number of Desktop Computers</label>
                    <input type="number" class="border rounded w-full p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Staff 1 Name</label>
                    <input type="text" class="border rounded w-full p-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Number of Laptop Computers</label>
                    <input type="number" class="border rounded w-full p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Position / Designation</label>
                    <input type="text" class="border rounded w-full p-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Internet Speed</label>
                    <div class="flex items-center gap-2">
                        <input type="text" class="border rounded w-20 p-2">
                        <label class="flex items-center"><input type="radio" name="speed" class="mr-1"> mbps</label>
                        <label class="flex items-center"><input type="radio" name="speed" class="mr-1"> gbps</label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Employment Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center"><input type="radio" name="employment" class="mr-1"> Regular</label>
                        <label class="flex items-center"><input type="radio" name="employment" class="mr-1"> Contractual</label>
                        <label class="flex items-center"><input type="radio" name="employment" class="mr-1"> Job Order</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 card">
            <h3>SECTORAL DATA</h3>

            <div class="form-row row">
                <div class="col-6">
                    <h5>FAMILIES LIVING IN DISASTER-PRONE AREAS</h5>
                </div>
                <div class="col-6">
                    <h5>VICTIM-SURVIVORS OF HUMAN TRAFFICKING</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of FLDPA <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Total Number of FLDPA (in figures)</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Date as of</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Source of Data</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <h5>INDIGENOUS PEOPLES FAMILIES (IPF)</h5>
                </div>
                <div class="col-6">
                    <h5>CHILDREN IN CONFLICT WITH LAW (CICL)</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of IPF <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Total Number of CICL (in figures)</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Date as of</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Source of Data</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <h5>SENIOR CITIZENS</h5>
                </div>
                <div class="col-6">
                    <h5>ABUSED, EXPLOITED, AND NEGLECTED CHILDREN</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of SC <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Total Number (in figures)</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Date as of</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Source of Data</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <h5>STREET FAMILIES</h5>
                </div>
                <div class="col-6">
                    <h5>VICTIMS OF CHILD TRAFFICKING</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of Street Families <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Total Number (in figures)</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Date as of</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Source of Data</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <h5>VIOLENCE AGAINST WOMEN (VAW) VICTIMS</h5>
                </div>
                <div class="col-6">
                    <h5>STREET CHILDREN</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of VAW victims <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Total Number (in figures)</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Date as of</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Source of Data</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <h5>PERSONS WITH DISABILITY</h5>
                </div>
                <div class="col-6">
                    <h5>SEXUALLY-ABUSED CHILDREN</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of PWD <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Total Number (in figures)</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Date as of</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
                <div class="col-6">
                    <label for="salaryGrade">Source of Data</label>
                    <input type="number" class="form-control" id="salaryGrade">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <h5>SOLO PARENTS</h5>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Total Number of Solo Parents <span class="text-red">(in figures)</span></label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Date as of</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
            </div>

            <div class="form-row row">
                <div class="col-6">
                    <label for="positionYears">Source of Data</label>
                    <input type="number" class="form-control" id="positionYears">
                </div>
            </div>

        </div>

        <div class="submit-assessment d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-lg btn-primary">Start Assessment</button>
        </div>

    </form>
</div>

@endsection
