@extends('laravel-admin::layout.dashboard')
@section('content')
    <section>
        <div class="container">
            <div class="mt-2 dashboard-title">
                Add Auth Group User
            </div>
            <form action="{{ route('auth_user_group.store') }}" method="post">
                @csrf
                @if (isset($auth_user_group))
                    <input type="hidden" name="auth_user_group_id" value="{{ $auth_user_group->id }}" />
                @endif
                <div class="mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-7">
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-2">
                                    <label for="inputName" class="col-form-label">Select User:</label>
                                </div>
                                <div class="col-sm-10">

                                    <select id="inputName" name="user_id" class="form-select"
                                        aria-describedby="passwordHelpInline">
                                        <option value="" selected disabled>Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                @if (isset($group_user) && $group_user->id == $user->id) selected @endif>
                                                {{ $user->first_name . ' ' . $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <span class="error-text" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mt-4 dashboard-title">
                    Groups
                </div>
                <div class="flex mt-3">
                    <div class="panel-width">
                        <div class="card shadow">
                            <div class="avail-permission-title font-bold">
                                Available Groups
                            </div>
                            <div class="p-3">
                                <div class="input-box">
                                    <input type="text" class="form-control" placeholder="Filter Groups"
                                        id="myAvailableSearch" onkeyup="myFunction()">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="px-4 mb-4 list-container">

                                <select class="form-select-custome" multiple aria-label="multiple select example"
                                    id="availableList">
                                    @foreach ($auth_groups as $data)
                                        <option value="{{ $data->id }}" class="custome">
                                            {{ $data->name }}
                                        </option>
                                    @endforeach

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
                                Choosen Groups
                            </div>
                            <div class="p-3">
                                <div class="input-box">
                                    <input type="text" class="form-control" placeholder="Filter Groups"
                                        id="myChooseListSearch" onkeyup="myChooseListFunction()">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="px-4 mb-4 list-container">

                                <select class="form-select-custome" name="choosen_groups[]" multiple
                                    aria-label="multiple select example" id="chosenList">
                                    @if (isset($choosen_groups))
                                        @foreach ($choosen_groups as $data)
                                            <option value="{{ $data->id }}" selected class="custome">
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                                @error('choosen_groups')
                                    <span class="" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

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
                        <button type="submit" class="button_slide slide_right_navy" name="action"
                            value="save">Save</button>
                    </div>
                    <div class="ml-10">
                        <button type="submit" class="button_slide_success slide_right" name="action"
                            value="save_and_add">Save and add another</button>
                    </div>
                    <div class="ml-10">
                        <button type="submit" class="button_slide_teel slide_info" name="action"
                            value="save_and_edit">Save and continue editing</button>
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
                $('#chosenList option').prop('selected', true);
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
                $('#chosenList option').prop('selected', true);
                $('#selectAllAvailable').removeClass('active-check-button');
            });

            $('#moveLeft').click(function() {
                // Move selected items from Chosen to Available
                $('#chosenList .custome.active').appendTo('#availableList');

                // Remove active class from all list items
                $('#availableList .custome.active').removeClass('active');
                $('#chosenList .custome.active').removeClass('active');
                $('#selectAllAvailable').addClass('active-check-button');
                $('#chosenList option').prop('selected', true);
                $('#selectAllChoos').removeClass('active-check-button');
            });

            // Toggle the active class on list items when clicked
            $('#availableList, #chosenList').on('click', '.custome', function() {
                $(this).toggleClass('active');
            });
        });
    </script>
@endsection
