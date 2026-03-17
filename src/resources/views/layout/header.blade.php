
    <nav class="navbar shadow-navbar">
        <div class="container d-flex justify-content-between">
            <div class="d-flex">
                <div class="d-none d-sm-none d-md-block d-lg-block">
                    <div id="sidebarCollapse" class="toggle-btn">
                        <i class="fa-solid fa-angles-left color-green toggle-icon"></i>
                    </div>
                </div>

                <div class="navbar-title mb-0 p">
                    Laravel administration</div>
            </div>
            <div class="user-tools">
                <div class="d-flex text-dark">
                    <div>
                        Welcome, <strong class="font-bold color-green">{{ auth()->user()->email ?? "" }}</strong>
                    </div>
                    <div class="d-flex">
                        {{--  <div>
                            <a href="#" class="header-link text-dark">View Site /</a>
                        </div>  --}}
                        <div>
                            <a href="{{ url(PREFIX_ADMIN_FOR_ROUTES . 'change-password') }}" class="header-link text-dark">Change Password /</a>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="header-link text-dark app_logout_btn">Log Out</a>
                        </div>
                        <form method="POST" action="{{ url('logout') }}" id="app_logout_form">
                            @csrf
                        </form>
                        <div id="theme-toggle" type="button" class="darkMode toggle-button ml-10 text-green">
                            <div id="theme-toggle-dark-icon" class="dark-icon ">
                                <svg class="moon-icon" width="20" height="20" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg>

                            </div>

                            <div id="theme-toggle-light-icon" class="light-icon">
                                <svg class="sun-icon" width="20" height="20" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    {{-- dark and light mode --}}
    <script>
        let darkModeState;

        const button = document.querySelector(".darkMode");
        const navLinks = document.querySelectorAll(".nav_link");
        const header = document.querySelector(".shadow-navbar");

        // MediaQueryList object
        const useDark = window.matchMedia("(prefers-color-scheme: dark)");

        // Toggles the "dark-mode" class
        function toggleDarkMode(state) {
            document.documentElement.classList.toggle("dark-mode", state);
            darkModeState = state;
        }

        // Sets localStorage state
        function setDarkModeLocalStorage(state) {
            localStorage.setItem("dark-mode", state);
        }

        // Initial setting
        toggleDarkMode(localStorage.getItem("dark-mode") == "true");

        // Listen for changes in the OS settings.
        // Note: the arrow function shorthand works only in modern browsers,
        // for older browsers define the function using the function keyword.
        useDark.addListener((evt) => toggleDarkMode(evt.matches));

        // Toggles the "dark-mode" class on click and sets localStorage state
        button.addEventListener("click", () => {
            darkModeState = !darkModeState;

            toggleDarkMode(darkModeState);
            setDarkModeLocalStorage(darkModeState);
        });
    </script>
