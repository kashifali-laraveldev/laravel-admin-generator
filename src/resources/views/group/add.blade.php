@extends('laravel-admin::layout.dashboard')
@section('content')
    <section>
        <div class="container">
            <div class="mt-2 dashboard-title">
                Add Group
            </div>
            <form>
                <div class="mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-7">
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-2">
                                    <label for="inputName" class="col-form-label">Name:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" id="inputName" class="form-control"
                                        aria-describedby="passwordHelpInline">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mt-4 dashboard-title">
                    Permissions
                </div>
                <div class="flex mt-3">
                    <div class="panel-width">
                        <div class="card shadow">
                            <div class="avail-permission-title font-bold">
                                Available Permissions
                            </div>
                            <div class="p-3">
                                <div class="input-box">
                                    <input type="text" class="form-control" placeholder="Filter Permissions"
                                        id="myAvailableSearch" onkeyup="myFunction()">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="px-4 mb-4 list-container">

                                <select class="form-select-custome" multiple aria-label="multiple select example"
                                    id="availableList">
                                    <option value="0" class="custome">
                                        Zero</option>
                                    <option value="1" class="custome">
                                        One</option>
                                    <option value="2" class="custome">
                                        Two</option>
                                    <option value="3" class="custome">
                                        Three</option>
                                    <option value="4" class="custome">
                                        Four</option>
                                    <option value="5" class="custome">
                                        Five</option>
                                    <option value="6" class="custome">
                                        Six</option>
                                </select>

                            </div>
                        </div>
                        <div class="choose-all-text mt-3 text-center active-check-button" id="selectAllAvailable">
                            Choose all <i class="fa-solid fa-angles-right icon-bg"></i>
                        </div>
                    </div>
                    <div class="align-self-center">
                        <div>
                            <div class="icons-container" id="moveRight">
                                <i class="fa-solid fa-arrow-right font-bold"></i>
                            </div>
                            <div class="icons-container mt-2" id="moveLeft">
                                <i class="fa-solid fa-arrow-left font-bold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="panel-width">
                        <div class="card shadow">
                            <div class="choosen-permission-title font-bold">
                                Choosen Permissions
                            </div>
                            <div class="p-3">
                                <div class="input-box">
                                    <input type="text" class="form-control" placeholder="Filter Permissions"
                                        id="myChooseListSearch" onkeyup="myChooseListFunction()">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="px-4 mb-4 list-container">

                                <select class="form-select-custome" multiple aria-label="multiple select example"
                                    id="chosenList">

                                </select>

                            </div>
                        </div>
                        <div class="remove-all-text mt-3 text-center" id="selectAllChoos">
                            <i class="fa-solid fa-angles-left icon-bg-remove"></i> Remove all
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center text-small font-bold">
                    Hold down “Control”, or “Command” on a Mac, to select more than one.
                </div>
                <div class="d-flex justify-content-center mt-5 mb-5">
                    <div>
                        <button class="button_slide slide_right_navy">Save</button>
                    </div>
                    <div class="ml-10">
                        <button class="button_slide_success slide_right">Save and add another</button>
                    </div>
                    <div class="ml-10">
                        <button class="button_slide_teel slide_info">Save and continue editing</button>
                    </div>
                </div>
            </form>
        </div>

    </section>
@endsection

@section('script')
    {{-- search --}}
    <script>
        //avaialbel list
        function myFunction() {
            var input, filter, ul, li, i, txtValue;
            input = document.getElementById('myAvailableSearch');
            filter = input.value.toLowerCase();
            ul = document.getElementById('availableList');
            li = ul.getElementsByTagName('option');

            for (i = 0; i < li.length; i++) {
                txtValue = li[i].textContent || li[i].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    li[i].style.display = '';
                } else {
                    li[i].style.display = 'none';
                }
            }
        }

        // choosen list
        function myChooseListFunction() {
            var input, filter, ul, li, i, txtValue;
            input = document.getElementById('myChooseListSearch');
            filter = input.value.toLowerCase();
            ul = document.getElementById('chosenList');
            li = ul.getElementsByTagName('option');

            for (i = 0; i < li.length; i++) {
                txtValue = li[i].textContent || li[i].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    li[i].style.display = '';
                } else {
                    li[i].style.display = 'none';
                }
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            // Function to select all available items
            $('#selectAllAvailable').click(function() {
                // Add the 'active' class to all list items in #availableList
                $('#availableList .custome').addClass('active');
                $('#availableList .custome.active').appendTo('#chosenList');
                $('#chosenList .custome.active').removeClass('active');
                $('#selectAllChoos').addClass('active-check-button');
                $('#selectAllAvailable').removeClass('active-check-button');
            });

            $('#selectAllChoos').click(function() {
                // Add the 'active' class to all list items in #availableList
                $('#chosenList .custome').addClass('active');
                $('#chosenList .custome.active').appendTo('#availableList');
                $('#availableList .custome.active').removeClass('active');
                $('#selectAllAvailable').addClass('active-check-button');
                $('#selectAllChoos').removeClass('active-check-button');
            })

            $('#moveRight').click(function() {
                // Move selected items from Available to Chosen
                $('#availableList .custome.active').appendTo('#chosenList');

                // Remove active class from all list items
                $('#availableList .custome.active').removeClass('active');
                $('#chosenList .custome.active').removeClass('active');
                $('#selectAllChoos').addClass('active-check-button');
                $('#selectAllAvailable').removeClass('active-check-button');
            });

            $('#moveLeft').click(function() {
                // Move selected items from Chosen to Available
                $('#chosenList .custome.active').appendTo('#availableList');

                // Remove active class from all list items
                $('#availableList .custome.active').removeClass('active');
                $('#chosenList .custome.active').removeClass('active');
                $('#selectAllAvailable').addClass('active-check-button');
                $('#selectAllChoos').removeClass('active-check-button');
            });

            // Toggle the active class on list items when clicked
            $('#availableList, #chosenList').on('click', '.custome', function() {
                $(this).toggleClass('active');
            });
        });
    </script>
@endsection
