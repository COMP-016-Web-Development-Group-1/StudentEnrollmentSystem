<header class="px-6 py-4 bg-white shadow-sm">
    <div class="max-w-screen-2xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-x-4">
            <i class="ti ti-school text-primary text-4xl"></i>
            <h1 class="text-xl font-bold">College Name</h1>
        </div>
        <nav class="space-x-12 hidden sm:flex">
            <a href="students.php" class="relative font-bold inline-block group rounded focus:outline-none
                focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary
                <?= uri_is('/students.php') ? 'text-primary' : 'text-black' ?>">
                <span class="relative z-10">Students</span>
                <span
                    class="absolute left-0 bottom-0 h-[2px] w-0 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="courses.php" class="relative font-bold inline-block group rounded focus:outline-none
                focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary
                <?= uri_is('/courses.php') ? 'text-primary' : 'text-black' ?>">
                <span class="relative z-10">Courses</span>
                <span
                    class="absolute left-0 bottom-0 h-[2px] w-0 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
        </nav>
        <div class="px-4 hidden items-center gap-x-2 cursor-pointer sm:flex">
            <img src="assets/images/admin_profile.png" class="w-12 h-12" alt="Admin Profile">
            <p class="font-bold">Admin</p>
            <i class="ti ti-chevron-down"></i>
        </div>

        <div class="flex items-center sm:hidden">
            <!-- Mobile menu button-->
            <button type="button" id="mobile-menu-button"
                class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-200 hover:text-primary focus:ring-2 focus:ring-primary focus:outline-none focus:ring-inset"
                aria-controls="mobile-menu" aria-expanded="false">
                <span class="absolute -inset-0.5"></span>
                <span class="sr-only">Open main menu</span>
                <!-- Icon when menu is closed -->
                <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true" data-slot="icon" id="menu-open-icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <!-- Icon when menu is open -->
                <svg class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true" data-slot="icon" id="menu-close-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu, hidden by default -->
    <div class="sm:hidden hidden mt-2 border-t border-gray-200 pt-2" id="mobile-menu">
        <div class="space-y-2 px-2 pb-3">
            <div class="flex items-center gap-x-2 py-2">
                <img src="assets/images/admin_profile.png" class="w-10 h-10" alt="Admin Profile">
                <p class="font-bold">Admin</p>
            </div>
            <a href="students.php" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100
            focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary
                <?= uri_is('/students.php') ? 'bg-gray-100 text-primary' : 'text-gray-700' ?>">
                Students
            </a>
            <a href="courses.php" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100
                focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary
                <?= uri_is('/courses.php') ? 'bg-gray-100 text-primary' : 'text-gray-700' ?>">
                Courses
            </a>

        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuOpenIcon = document.getElementById('menu-open-icon');
        const menuCloseIcon = document.getElementById('menu-close-icon');

        mobileMenuButton.addEventListener('click', () => {
            const isMenuOpen = mobileMenu.classList.contains('hidden');

            // Toggle mobile menu visibility
            if (isMenuOpen) {
                mobileMenu.classList.remove('hidden');
                menuOpenIcon.classList.add('hidden');
                menuCloseIcon.classList.remove('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'true');
            } else {
                mobileMenu.classList.add('hidden');
                menuOpenIcon.classList.remove('hidden');
                menuCloseIcon.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>