/**
* Theme: Lunoz - Responsive Tailwind Admin Dashboard
* Author: Myra Studio
* Module/App: App js
*/

class App {

    constructor() {
        this.html = document.getElementsByTagName('html')[0]
        this.config = {};
        this.defaultConfig = window.config;
    }

    initComponents() {
        Waves.init()

        $(function () {
            $('[data-plugin="knob"]').knob();
        });
    }

    initSidenav() {
        var self = this;
        var pageUrl = window.location.href.split(/[?#]/)[0];
        document.querySelectorAll('ul.menu a.menu-link').forEach((element) => {
            if (element.href === pageUrl) {
                element.classList.add('active');
                let parentMenu = element.parentElement.parentElement.parentElement;
                if (parentMenu && parentMenu.classList.contains('menu-item')) {
                    const collapseElement = parentMenu.querySelector('[data-fc-type="collapse"]');
                    if (collapseElement && frost != null) {
                        const collapse = frost.Collapse.getInstanceOrCreate(collapseElement);
                        collapse.show();
                    }
                }
            }
        })

        setTimeout(function () {
            var activatedItem = document.querySelector('ul.menu .active');
            if (activatedItem != null) {
                var simplebarContent = document.querySelector('.app-menu .simplebar-content-wrapper');
                var offset = activatedItem.offsetTop - 300;
                if (simplebarContent && offset > 100) {
                    scrollTo(simplebarContent, offset, 600);
                }
            }
        }, 200);

        // scrollTo (Sidenav Active Menu)
        function easeInOutQuad(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        function scrollTo(element, to, duration) {
            var start = element.scrollTop, change = to - start, currentTime = 0, increment = 20;
            var animateScroll = function () {
                currentTime += increment;
                var val = easeInOutQuad(currentTime, start, change, duration);
                element.scrollTop = val;
                if (currentTime < duration) {
                    setTimeout(animateScroll, increment);
                }
            };
            animateScroll();
        }
    }

    reverseQuery(element, query) {
        while (element) {
            if (element.parentElement) {
                if (element.parentElement.querySelector(query) === element) return element
            }
            element = element.parentElement;
        }
        return null;
    }


    initSwitchListener() {
        var self = this;
        // Menu Toggle Button ( Placed in Topbar)
        var html = document.getElementsByTagName("html")[0];
        var menuToggleBtn = document.querySelector('#button-toggle-menu');
        if (menuToggleBtn) {
            menuToggleBtn.addEventListener('click', function () {
                var view = self.html.getAttribute('data-sidebar-view');

                if (view === 'mobile') {
                    self.showBackdrop();
                    self.html.classList.toggle('sidebar-open');
                } else {
                    if (view === 'hidden') {
                        html.setAttribute("data-sidebar-view", "default");
                    } else {
                        html.setAttribute("data-sidebar-view", "hidden");
                    }
                }
            });
        }


        // Menu Toggle Button ( Placed in Topbar)
        var html = document.getElementsByTagName("html")[0];
        var profileToggleBtn = document.querySelector('#button-toggle-profile');
        if (profileToggleBtn) {
            profileToggleBtn.addEventListener('click', function () {
                var view = self.html.getAttribute('data-profile-view');

                if (view === 'mobile') {
                    self.showBackdrop();
                    self.html.classList.toggle('profile-open');
                } else {
                    if (view === 'hidden') {
                        html.setAttribute("data-profile-view", "default");
                    } else {
                        html.setAttribute("data-profile-view", "hidden");
                    }
                }
            });
        }
    }



    showBackdrop() {
        const backdrop = document.createElement('div');
        backdrop.id = 'backdrop';
        backdrop.classList = 'transition-all fixed inset-0 z-40 bg-gray-900 bg-opacity-50';
        document.body.appendChild(backdrop);

        if (document.getElementsByTagName('html')[0]) {
            document.body.style.overflow = "hidden";
            if (window.innerWidth > 1140) {
                document.body.style.paddingRight = "17px";
            }
        }

        const self = this
        backdrop.addEventListener('click', function (e) {
            self.html.classList.remove('sidebar-open');
            self.html.classList.remove('profile-open');
            self.hideBackdrop();
        })
    }

    hideBackdrop() {
        var backdrop = document.getElementById('backdrop');
        if (backdrop) {
            document.body.removeChild(backdrop);
            document.body.style.overflow = null;
            document.body.style.paddingRight = null;
        }
    }

    // Topbar Fullscreen Button
    initfullScreenListener() {
        var self = this;
        var fullScreenBtn = document.querySelector('[data-toggle="fullscreen"]');

        if (fullScreenBtn) {
            fullScreenBtn.addEventListener('click', function (e) {
                e.preventDefault();
                document.body.classList.toggle('fullscreen-enable')
                if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullscreen) {
                        document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    }
                }
            });
        }
    }

    init() {
        this.initComponents();
        this.initSidenav();
        this.initSwitchListener();
        this.initfullScreenListener();
    }
}

new App().init();