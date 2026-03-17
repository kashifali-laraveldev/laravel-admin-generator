@extends('layout.dashboard')
@section('content')
    <section>
        <div>
            <div class="ms-auto text-end pb-5">
                <a href="/add_auth_group_user" class="button_slide slide_right_navy">+ Add New</a>
            </div>
        </div>
        <div class="card p-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 border-radius-top-left">#</th>
                            <th scope="col" class="p-3">First</th>
                            <th scope="col" class="p-3">Last</th>
                            <th scope="col" class="p-3 border-radius-top-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr>
                            <td scope="row">1</td>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>
                                <a href="/edit_auth_group_user">
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                </a>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">3</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">4</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">5</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">6</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-medium mt-2">Showing 1 to 10 of 11 entries</p>
                </div>
                <div>
                    {{-- pagination --}}
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item" aria-current="page">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
