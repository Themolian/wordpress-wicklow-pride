const menuToggle = function () {
  // Toggle mobile navigation
  let menuToggle = document.querySelector(".menu-toggle");
  let menuItems = document.querySelectorAll(".mobile-navigation ul li");
  let body = document.querySelector("body");

  menuToggle.addEventListener("click", function (e) {
    e.target.classList.toggle("active");
    document.querySelector(".mobile-navigation").classList.toggle("active");
    body.classList.toggle("no-scroll");
  });

  menuItems.forEach(function (item) {
    item.addEventListener("click", function () {
      menuToggle.classList.remove("active");
      document.querySelector(".mobile-navigation").classList.remove("active");
      body.classList.remove("no-scroll");
    });
  });
};

export { menuToggle };
