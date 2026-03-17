@extends('layout.app')
@section('content')
    <div class="container">
        <div class="dashboard-title">
            Site administration
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div>
                    <table class="table mt-4">
                        <thead class="bg-theme">
                            <tr class="table-head">
                                <th scope="col" class="w-80">App Name</th>
                                <th scope="col" class="w-10"></th>
                                <th scope="col" class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="color-theme font-semi-bold">Assessment levels</td>
                                <td class="cursor"><i class="fa-solid fa-plus color-green font-bold"></i> Add</td>
                                <td class="cursor"><i class="fa-solid fa-pencil color-yellow"></i> Change</td>
                            </tr>
                            <tr>
                                <td class="color-theme font-semi-bold">Assessment levels</td>
                                <td class="cursor"><i class="fa-solid fa-plus color-green font-bold"></i> Add</td>
                                <td class="cursor"><i class="fa-solid fa-pencil color-yellow"></i> Change</td>
                            </tr>
                            <tr>
                                <td class="color-theme font-semi-bold">Assessment levels</td>
                                <td class="cursor"><i class="fa-solid fa-plus color-green font-bold"></i> Add</td>
                                <td class="cursor"><i class="fa-solid fa-pencil color-yellow"></i> Change</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mt-4">
                    <div class="right-panle">
                        <div class="title-right-panel">
                            Recent Actions
                        </div>
                        <hr>
                        <div class="my-action-title">
                            My Action
                        </div>
                        <p class="mt-3">Non Available</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
