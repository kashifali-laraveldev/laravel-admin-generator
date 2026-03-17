@extends('layout.dashboard')
@section('content')
    <section>
        <div>
            <h4 class="dashboard-title">Auth Group Users</h4>
        </div>
        <div>
            <div class="card p-4 card-shadow mt-4">
                <form action="#">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                    placeholder="Input Element">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="mb-3">
                                <input class="form-control" type="file" id="formFile">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mt-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check ml-10">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Default radio
                                    </label>
                                </div>
                                <div class="form-check form-switch ml-10">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Default switch
                                        checkbox</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="">
                                <textarea class="form-control" placeholder="Leave a comment here..." id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <input class="form-control" list="datalistOptions" id="exampleDataList"
                                placeholder="Type to search...">
                            <datalist id="datalistOptions">
                                <option value="San Francisco">
                                <option value="New York">
                                <option value="Seattle">
                                <option value="Los Angeles">
                                <option value="Chicago">
                            </datalist>
                        </div>
                    </div>
                    <div class="mt-5 d-flex justify-content-center">
                        <button class="button_slide slide_right_navy">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
